<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/22
 * Time: 15:52
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\Number;

class UploaderComponent extends Component
{
    protected $_defaultConfig = [
        'upType' => 'upfile',
        'fileField' => 'file',
        'maxSize' => '307200000',
        'basePath' => WWW_ROOT.'files/' ,
        'allowedExts' => array(
            'image/png',
            'image/gif',
            'image/jpg',
            'image/jpeg',
            'video/mp4',
            'text/csv',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword'),
        'format' => '{yy}{mm}{dd}{hh}{ii}{ss}{rand:5}'
    ];

    private $_d; //上传日期数组
    private $_basePath; //存储目录
    private $_maxSize; //上传限制大小
    private $_upType; //使用的上传方式
    private $_fileField; //文件域名
    private $_allowedExts;//允许的后缀，含"."

    private $_file; //上传的文件
    private $_oriName; //原始文件名
    private $_fileName; //新文件名
    private $_fullName; //完整文件名,包含完整路径
    private $_filePath; //完整目录
    private $_fileSize; //文件大小
    private $_fileType; //文件类型
    private $_format; //新文件名格式

    public function initialize(array $config): void
    {
        $this->_upType = $this->getConfig('upType');
        $this->_fileField = $this->getConfig('fileField');
        $this->_format = $this->getConfig('format');
        $this->_basePath = $this->getConfig('basePath');
        $this->_maxSize = $this->getConfig('maxSize');
        $this->_allowedExts = $this->getConfig('allowedExts');
        $this->_d = explode('-', date('Y-y-m-d-H-i-s'));
    }

    public function upload(array $config = [])
    {
        $this->_upType =  $config['upType'] ?? $this->_upType;
        $this->_fileField = $config['fileField'] ?? $this->_fileField;
        $this->_format = $config['format'] ?? $this->_format;
        $this->_basePath = $config['basePath'] ?? $this->_basePath;
        $this->_maxSize = $config['maxSize'] ?? $this->_maxSize;
        $this->_allowedExts = $config['allowedExts'] ?? $this->_allowedExts;

        $controller = $this->getController();
        $this->_file = $controller->getRequest()->getData($this->_fileField);
        if ($this->_upType === 'base64') {
            $this->_oriName = $config['oriName'] ?? 'unknown.png';
            return $this->_upBase64();
        } else if($this->_upType === 'remote'){
            return $this->_upRemote();
        } else {
            return $this->_upFile();
        }
    }

    private function _upFile()
    {
        $file = $this->_file;
        $tmpFile = $file->getStream()->getMetadata('uri');
        if (!$file) {
            return $this->_error('ERROR_FILE_NOT_FOUND');
        }

        if ($file->getError()) {
            return $this->_error($file->getError());
        }

        if (!file_exists($tmpFile)) {
            return $this->_error('ERROR_TMP_FILE_NOT_FOUND');
        }

        if (!is_uploaded_file($tmpFile)) {
            return $this->_error('ERROR_TMPFILE');
        }

        $this->_oriName = $file->getClientFilename();
        $this->_fileSize = $file->getSize();
        $this->_fileType = $this->_getFileExt();
        $this->_fileName = $this->_getFileName();
        $this->_filePath = $this->_getFilePath();
        $this->_fullName = $this->_filePath  . $this->_fileName;

        if (!$this->_checkSize()) {
            return $this->_error('ERROR_UPLOAD_MAX_FILESIZE');
        }

        if (!$this->_checkType()) {
            return $this->_error('ERROR_TYPE_NOT_ALLOWED');
        }

        $folder = new Folder();
        if (!$folder->create($this->_basePath . $this->_filePath)) {
            return $this->_error('ERROR_CREATE_DIR');
        }

        $f = new File($tmpFile);
        if(!$f->copy($this->_basePath . $this->_fullName)) {
            $f->close();
            return $this->_error('ERROR_FILE_MOVE');
        }
        $f->close();

        return $this->_success([
            'oriName' => $this->_oriName,
            'fullName' => $this->_fullName,
            'type' => $this->_fileType,
            'size' => Number::toReadableSize($this->_fileSize)
        ]);

    }

    private function _upBase64()
    {
        $img = $this->_urlsafeB64Decode($this->_file);
;
        $this->_fileSize = strlen($img);
        $this->_fileType = $this->_getFileExt();
        $this->_fileName = $this->_getFileName();
        $this->_filePath = $this->_getFilePath();
        $this->_fullName = $this->_filePath  . $this->_fileName;

        if (!$this->_checkSize()) {
            return $this->_error('ERROR_UPLOAD_MAX_FILESIZE');
        }

        if (!$this->_checkType()) {
            return $this->_error('ERROR_TYPE_NOT_ALLOWED');
        }

        $folder = new Folder();
        if (!$folder->create($this->_basePath . $this->_filePath)) {
            return $this->_error('ERROR_CREATE_DIR');
        }

        $file = new File($this->_basePath . $this->_fullName, true);
        if(!$file->write($img)) {
            $file->close();
            return $this->_error('ERROR_WRITE_CONTENT');
        }
        $file->close();

        return $this->_success([
            'oriName' => $this->_oriName,
            'fullName' => $this->_fullName,
            'type' => $this->_fileType,
            'size' => Number::toReadableSize($this->_fileSize)
        ]);
    }

    private function _upRemote()
    {
        $imgUrl = htmlspecialchars($this->_file);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        $pathRes     = parse_url($imgUrl);
        $queryString = isset($pathRes['query']) ? $pathRes['query'] : '';
        $imgUrl      = str_replace('?' . $queryString, '', $imgUrl);

        if (strpos($imgUrl, 'http') !== 0) {
            return $this->_error('ERROR_HTTP_LINK');
        }

        //获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            return $this->_error('ERROR_DEAD_LINK');
        }

        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->_allowedExts) || !isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            return $this->_error('ERROR_HTTP_CONTENTTYPE');
        }

        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl . '?' . $queryString, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->_oriName = $m ? $m[1] : '';
        $this->_fileSize = strlen($img);
        $this->_fileType = $this->_getFileExt();
        $this->_fileName = $this->_getFileName();
        $this->_filePath = $this->_getFilePath();
        $this->_fullName = $this->_filePath  . $this->_fileName;

        if (!$this->_checkSize()) {
            return $this->_error('ERROR_UPLOAD_MAX_FILESIZE');
        }

        if (!$this->_checkType()) {
            return $this->_error('ERROR_TYPE_NOT_ALLOWED');
        }

        $folder = new Folder();
        if (!$folder->create($this->_basePath . $this->_filePath)) {
            return $this->_error('ERROR_CREATE_DIR');
        }

        $file = new File($this->_basePath . $this->_fullName, true);
        if(!$file->write($img)) {
            $file->close();
            return $this->_error('ERROR_WRITE_CONTENT');
        }

        if (substr($file->mime(), 0, 5) != 'image') {
            $file->delete();
            $file->close();
            return $this->_error('ERROR_TYPE_NOT_ALLOWED');
        }

        $file->close();

        return $this->_success([
            'oriName' => $this->_oriName,
            'fullName' => $this->_fullName,
            'type' => $this->_fileType,
            'size' => Number::toReadableSize($this->_fileSize)
        ]);

    }

    private function _checkType()
    {
        return in_array($this->_file->getClientMediaType(), $this->_allowedExts);
    }

    private function _checkSize()
    {
        return $this->_fileSize <= ($this->_maxSize);
    }

    private function _getFilePath()
    {
        $d = $this->_d;
        $path = $d[1] . DS . $d[2] . DS . $d[3] . DS;
        return $path;
    }

    private function _getFileExt()
    {
        return strtolower(strrchr($this->_oriName, '.'));
    }

    private function _getFileName()
    {
        $t = time();
        $d = $this->_d;
        $format = $this->_format;
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);
        //过滤文件名的非法字符,并替换文件名
        $oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);
        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        return $format . $this->_fileType;
    }

    private function _urlsafeB64Decode($input)
    {
        $input = strstr($input, ',');
        $input = trim($input, ',');

        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private function _error($msg)
    {
        return $this->_result(400, $msg);
    }

    private function _success($data = null)
    {
        return $this->_result(200, 'success', $data);
    }

    private function _result($status, $msg, $data = null)
    {
        $info = [
            'ERROR_UPLOAD_MAX_FILESIZE' => '文件大小超出了上传限制',
            'ERROR_TMP_FILE' => '临时文件错误',
            'ERROR_TMP_FILE_NOT_FOUND' => '找不到临时文件',
            'ERROR_SIZE_EXCEED' => '文件大小超出网站限制',
            'ERROR_TYPE_NOT_ALLOWED' => '文件类型不允许',
            'ERROR_CREATE_DIR' => '目录创建失败',
            'ERROR_DIR_NOT_WRITEABLE' => '目录没有写权限',
            'ERROR_FILE_MOVE' => '文件保存时出错',
            'ERROR_FILE_NOT_FOUND' => '找不到上传文件',
            'ERROR_WRITE_CONTENT' => '写入文件内容错误',
            'ERROR_UNKNOWN' => '未知错误',
            'ERROR_DEAD_LINK' => '链接不可用',
            'ERROR_HTTP_LINK' => '链接不是http链接',
            'ERROR_HTTP_CONTENTTYPE' => '链接contentType不正确'
        ];

        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
