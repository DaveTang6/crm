-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 03, 2024 at 09:37 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kkcrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT '密码，使用Md5加密',
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `truename` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `salt` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_time` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `last_ip` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `group_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `visit_count` int(11) DEFAULT '0',
  `locked` tinyint(4) DEFAULT '0' COMMENT '锁定：0_未锁定,1_锁定',
  `enabled` tinyint(4) DEFAULT '1' COMMENT '是否可用：0_不可用，1_可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `mobile`, `truename`, `salt`, `reg_time`, `last_login`, `last_ip`, `group_id`, `visit_count`, `locked`, `enabled`) VALUES
(4, 'admin', '859d990ab3221365058801fe4f746e72', '', '13108970911', '唐的雾', '786312', 1646968273, 1724521436, '127.0.0.1', '[1]', 112, 0, 1),
(10, '三石Leo', '25c21cf69c44ae873595cc688c50a880', '', '', '田磊', '234310', 1662357168, 1699000136, '14.111.242.46', '[5]', 68, 0, 1),
(12, 'zoe', '7a48144fe47a3485cf01b0329c57ad89', '', '', '唐倩', '792076', 1662426513, 1700127079, '14.111.240.139', '[9]', 30, 0, 1),
(13, '沈丽', 'c28c8eade35e908050650c98c996c75a', '', '', '沈丽', '160356', 1662426652, 1693994218, '14.111.240.185', '[9]', 48, 0, 0),
(14, '傅晶', '38985886f32aaca217acb7c128f26ae2', '', '', '傅晶', '980994', 1662426749, 1700442095, '14.111.242.121', '[7]', 124, 0, 1),
(15, '叶远伟', '3c7016fb4fe1415c33109b8e07fc6947', '', '', '叶远伟', '792918', 1662426785, 1700530762, '14.111.242.121', '[7,6]', 64, 0, 1),
(16, '周雨阳', '3f2e012d62206e98820d373548ea7e31', '', '', '周雨阳', '983829', 1662426797, 1686101118, '14.111.240.105', '[7]', 33, 0, 1),
(17, '喻翔', '1cefea5dd2cda794122d45705f70f0a2', '', '', '喻翔', '964531', 1662426810, 1700450250, '14.111.242.121', '[7]', 118, 0, 1),
(18, '吴少波', '70356ba87bb2f9dd9d16617939351969', '', '', '吴少波', '124975', 1662426823, 1687686870, '14.111.242.83', '[7]', 25, 0, 1),
(20, '李双鹏', '29c6be5bd09bd1610834e8847c009dae', '3083728279@qq.com', '15902399323', '李双鹏', '223677', 1662426860, 1700112893, '14.111.240.139', '[7]', 45, 0, 1),
(21, '李秋楠', '59cb9bac3e47af9fe53d913ba7eefaf3', '', '', '李秋楠', '794342', 1662436266, 1700188412, '14.111.240.139', '[7]', 15, 0, 1),
(22, '秋秋', '3dc978a4b659bfd4e7f02f8bb1b3696a', '', '', '秋秋', '164191', 1662514493, 1663229670, '106.87.40.67', '[7]', 2, 0, 1),
(23, 'tester', '82001edb7f93276c6b6f486d17c20548', '', '', '测试员', '688696', 1662600932, 1664251212, '119.86.73.251', '[6]', 6, 0, 1),
(26, '李健', '24a4afa788b86748db0b2a32137d5299', '', '', '李健', '216032', 1663825295, 1700472206, '113.104.236.161', '[7]', 209, 0, 1),
(27, '曾绮', '89e9df2db7ff0fb10a1499a2fea00bb0', '', '', '曾绮', '187229', 1663825316, 1683250783, '113.116.41.204', '[7]', 28, 0, 0),
(29, '尚月', '120f64b61f477147bd4ee8554e4b4602', '', '', '尚月', '866975', 1663825344, 1700099631, '116.7.11.188', '[7]', 53, 0, 1),
(30, '向姝樾', '8ac9043d77d016db175702c3615f6b6a', '', '', '向姝樾', '882827', 1664012293, 1700187092, '14.111.240.139', '[7]', 2, 0, 1),
(32, '曾红铫', '9318854926c942bcdb1564b4affd0f27', '', '', '曾红铫', '850605', 1664012328, 1684910011, '14.111.240.231', '[7]', 107, 0, 0),
(34, '何立彬', '82b682698a466e341c271b8d67501c5f', '', '', '何立彬', '488465', 1664012545, 1700555745, '113.104.236.161', '[7]', 8, 0, 1),
(37, '李姝梦', '29359f6b3449e6fd896ad3ecc2bd98d0', '', '', '李姝梦', '210780', 1664334627, 1700211766, '14.111.240.139', '[8]', 57, 0, 1),
(38, 'financer', 'bf330864a15e43954d5d7c9c1e87049f', '', '', '财务测试', '987491', 1664334816, 1664415197, '119.8.116.19', '[8]', 3, 0, 1),
(42, '李林柯', 'd46cffa7d67cbb9294bdb8e752cd075f', '', '', '李林柯', '268979', 1677035186, 1679540756, '14.111.241.72', '[8]', 2, 0, 1),
(43, '程静', '2c2e45f9bf1e41cf5756997f62414bc0', '', '', '程静', '464687', 1678414990, 1700551385, '14.111.242.121', '[7]', 40, 0, 1),
(45, '曾雅琳', '03dfe2bdbb30ddadf752a602cf4132ca', '', '', '曾雅琳', '907919', 1678870266, 1700466390, '119.123.33.255', '[9]', 33, 0, 1),
(47, '李元龙', '13726b0bcf285c74d1dda46123ce7c7e', '', '', '李元龙', '109874', 1691981881, 1698906380, '113.116.43.148', '[6,7]', 5, 0, 1),
(48, '张延坤', '3727859fccb0c9fdd212cdc4501cc472', '', '', '张延坤', '219000', 1692442595, 1700194345, '116.7.11.188', '[7]', 10, 0, 1),
(50, '李晨辉', 'e90beb469f33f3b4c0bc1418ef7ffe9c', '', '', '李晨辉', '447142', 1698116527, 1700530133, '14.111.242.121', '[9,5]', 39, 0, 1),
(51, '阙旭坚', 'f110be250820cda8d14f57437c158a70', '', '', '阙旭坚', '791064', 1698116618, 1699933217, '116.7.11.188', '[7]', 3, 0, 1),
(52, '唐玉花', 'c3bedbb28f40107d7973ce65efc55dc1', '', '', '唐玉花', '136284', 1698117068, NULL, NULL, '[9]', 0, 0, 1),
(53, '高银娟', '509c17fa5a4ba4198629278e8b9fa6cc', '', '', '高银娟', '760448', 1698117084, 1698117559, '14.111.246.141', '[9]', 1, 0, 1),
(54, '廖秋艺', '5670a9b5eda08016cb915a2599715b65', '', '', '廖秋艺', '982948', 1698117095, 1698117565, '14.111.246.141', '[9]', 1, 0, 1),
(55, '杨庆婷', '29998ffa4cbad01414e5136830429a2a', '', '', '杨庆婷', '168020', 1698117883, NULL, NULL, '[7]', 0, 1, 1),
(56, '杨杰', '6a74a191f8998e6fb83ab817568a491f', '', '', '杨杰', '940106', 1698120045, 1698133409, '14.111.246.141', '[6]', 1, 0, 1),
(57, '林婷', '79664f72b5a7863cc995c37a7e203344', '', '', '林婷', '482072', 1698828964, 1700458927, '113.104.236.161', '[9]', 11, 0, 1),
(58, '王小丹', 'f8e4ac3414414dde8e0c9f6b4d35a435', '', '', '王小丹', '942859', 1698892387, 1700626320, '14.111.242.121', '[8]', 9, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_acos`
--

CREATE TABLE `admin_acos` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级菜单(若本身为顶级菜单，用0代替)',
  `title` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `alias` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '别名',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `memo` text CHARACTER SET utf8,
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，0-不可用，1-可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';

--
-- Dumping data for table `admin_acos`
--

INSERT INTO `admin_acos` (`id`, `parent_id`, `title`, `alias`, `lft`, `rght`, `memo`, `status`) VALUES
(1, NULL, '管理员', NULL, 1, 18, '[]', 1),
(3, 1, '新增管理员', 'adminAdd', 2, 3, '[{\"r\":\"admins\\/add\",\"p\":0}]', 1),
(10, NULL, '系统设置', NULL, 19, 24, '[]', 1),
(15, 1, '删除管理员', 'adminDelete', 4, 5, '[{\"r\":\"admins\\/delete\",\"p\":0}]', 1),
(16, 1, '管理员修改', NULL, 6, 7, '[{\"r\":\"admins\\/update\",\"p\":0},{\"r\":\"admins\\/view\",\"p\":0}]', 1),
(17, 1, '个人设置', NULL, 8, 9, '[{\"r\":\"admins\\/selfupdate\",\"p\":0},{\"r\":\"admins\\/selfview\",\"p\":0}]', 1),
(18, 1, '登录', NULL, 10, 11, '[{\"r\":\"admins\\/login\",\"p\":0}]', 1),
(19, 1, '设置管理员状态', NULL, 12, 13, '[{\"r\":\"admins\\/setenabled\",\"p\":0}]', 1),
(20, 1, '状态解锁', NULL, 14, 15, '[{\"r\":\"admins\\/setunlocked\",\"p\":0}]', 1),
(21, 10, '登录控制', NULL, 20, 21, '[{\"r\":\"hardconfigs\\/update\\/admin_control\",\"p\":0},{\"r\":\"hardconfigs\\/view\\/admin_control\",\"p\":0}]', 1),
(22, NULL, '管理组', NULL, 25, 36, '[]', 1),
(23, 22, '管理组列表', NULL, 26, 27, '[{\"r\":\"adminaros\\/getlist\",\"p\":0}]', 1),
(24, 1, '管理员列表', NULL, 16, 17, '[{\"r\":\"admins\\/index\",\"p\":0},{\"r\":\"adminaros\\/getlist\",\"p\":0}]', 1),
(25, 22, '新增管理组', NULL, 28, 29, '[{\"r\":\"adminaros\\/add\",\"p\":0}]', 1),
(26, 22, '管理组修改', NULL, 30, 31, '[{\"r\":\"adminaros\\/update\",\"p\":0},{\"r\":\"adminaros\\/view\",\"p\":0}]', 1),
(27, 22, '管理组复制', NULL, 32, 33, '[{\"r\":\"adminaros\\/copyaro\",\"p\":0}]', 1),
(28, 22, '删除管理组', NULL, 34, 35, '[{\"r\":\"adminaros\\/delete\",\"p\":0}]', 1),
(29, 30, '获取个人权限', 'getRoles', 38, 39, '[{\"r\":\"adminaros\\/getselfaros\",\"p\":0},{\"r\":\"sysmenus\\/getmenu\",\"p\":0}]', 1),
(30, NULL, '权限', NULL, 37, 50, '[]', 1),
(31, 30, '权限列表', NULL, 40, 41, '[{\"r\":\"adminacos\\/getkeypath\",\"p\":0},{\"r\":\"adminacos\\/getlist\",\"p\":0}]', 1),
(32, 30, '新增权限', NULL, 42, 43, '[{\"r\":\"adminacos\\/add\",\"p\":0}]', 1),
(33, 30, '修改权限', NULL, 44, 45, '[{\"r\":\"adminacos\\/view\",\"p\":0},{\"r\":\"adminacos\\/update\",\"p\":0}]', 1),
(34, 30, '删除权限', NULL, 46, 47, '[{\"r\":\"adminacos\\/delete\",\"p\":0}]', 1),
(35, 30, '权限移动', NULL, 48, 49, '[{\"r\":\"adminacos\\/move\",\"p\":0}]', 1),
(36, NULL, '合同管理', '', 51, 74, '[]', 1),
(37, 36, '新增合同分类', '', 52, 53, '[{\"r\":\"contractcategories\\/add\",\"p\":0}]', 1),
(38, 36, '合同分类修改', '', 54, 55, '[{\"r\":\"contractcategories\\/update\",\"p\":0},{\"r\":\"contractcategories\\/move\",\"p\":0},{\"r\":\"contractcategories\\/view\",\"p\":0}]', 1),
(39, 36, '获取合同分类', '', 56, 57, '[{\"r\":\"contractcategories\\/getlist\",\"p\":0},{\"r\":\"contractcategories\\/getkeypath\",\"p\":0}]', 1),
(40, 36, '删除合同分类', '', 58, 59, '[{\"r\":\"contractcategories\\/delete\",\"p\":0}]', 1),
(41, 36, '合同分类查看', '', 60, 61, '[{\"r\":\"contractcategories\\/view\",\"p\":0}]', 1),
(42, 36, '新增合同', '', 62, 63, '[{\"r\":\"contracts\\/add\",\"p\":0},{\"r\":\"commons\\/updatefile\",\"p\":0}]', 1),
(43, 36, '合同列表', '', 64, 65, '[{\"r\":\"contracts\\/index\",\"p\":0}]', 1),
(44, 36, '设置合同状态', '', 66, 67, '[{\"r\":\"contracts\\/setstatus\",\"p\":0}]', 1),
(45, 36, '删除合同', '', 68, 69, '[{\"r\":\"contracts\\/delete\",\"p\":0}]', 1),
(46, 36, '修改合同', '', 70, 71, '[{\"r\":\"contracts\\/update\",\"p\":0},{\"r\":\"contracts\\/view\",\"p\":0},{\"r\":\"commons\\/updatefile\",\"p\":0}]', 1),
(47, 36, '查看合同', '', 72, 73, '[{\"r\":\"contracts\\/view\",\"p\":0}]', 1),
(48, NULL, '客户管理', '', 75, 116, '[]', 1),
(49, 48, '新增客户', '', 76, 77, '[{\"r\":\"customers\\/add\",\"p\":0}]', 1),
(50, 48, '我的客户', '', 78, 79, '[{\"r\":\"customers\\/mycustomers\",\"p\":0}]', 1),
(51, 48, '部门客户', '', 80, 81, '[{\"r\":\"customers\\/departmentcustomers\",\"p\":0}]', 1),
(52, 48, '客户池', '', 82, 83, '[{\"r\":\"customerpools\\/customers\",\"p\":0}]', 1),
(53, 48, '删除客户', '', 84, 85, '[{\"r\":\"customers\\/delete\",\"p\":0}]', 1),
(54, 48, '修改客户', '', 86, 87, '[{\"r\":\"customers\\/update\",\"p\":0},{\"r\":\"customers\\/view\",\"p\":0}]', 1),
(55, 48, '查看客户资料', '', 88, 89, '[{\"r\":\"customers\\/view\",\"p\":0}]', 1),
(56, 48, '新增拜访记录', '', 90, 91, '[{\"r\":\"customersalelogs\\/add\",\"p\":0}]', 1),
(57, 48, '修改拜访记录', '', 92, 93, '[{\"r\":\"customersalelogs\\/update\",\"p\":0}]', 1),
(58, 48, '获取拜访记录', '', 94, 95, '[{\"r\":\"customersalelogs\\/index\",\"p\":0}]', 1),
(59, 48, '删除拜访记录', '', 96, 97, '[{\"r\":\"customersalelogs\\/delete\",\"p\":0}]', 1),
(60, 48, '更新客户池备注', '', 98, 99, '[{\"r\":\"customerpools\\/updatememo\",\"p\":0}]', 1),
(61, 48, '释放客户池客户', '', 100, 101, '[{\"r\":\"customerpools\\/releasecustomers\",\"p\":0}]', 1),
(62, 48, '释放客户池客户', '', 102, 103, '[{\"r\":\"customerpools\\/releasecustomers\",\"p\":0}]', 1),
(63, 48, '分配客户池客户', '', 104, 105, '[{\"r\":\"customerpools\\/assigncustomers\",\"p\":0}]', 1),
(64, 48, '查看客户池客户', '', 106, 107, '[{\"r\":\"customerpools\\/view\",\"p\":0}]', 1),
(65, 48, '客户操作日志', '', 108, 109, '[{\"r\":\"customeractlogs\\/actlogs\",\"p\":0}]', 1),
(66, 48, '客户独占查询', '', 110, 111, '[{\"r\":\"customerpools\\/findcustomers\",\"p\":0}]', 1),
(67, NULL, '部门人员', '', 117, 128, '[]', 1),
(68, 67, '获取全部人员', '', 118, 119, '[{\"r\":\"departments\\/index\",\"p\":0}]', 1),
(69, 67, '获取部门人员', '', 120, 121, '[{\"r\":\"departments\\/getstaffs\",\"p\":0}]', 1),
(70, 67, '删除部门人员', '', 122, 123, '[{\"r\":\"departments\\/delete\",\"p\":0}]', 1),
(71, 67, '新增部门员工', '', 124, 125, '[{\"r\":\"departments\\/addstaff\",\"p\":0},{\"r\":\"admins\\/getadmins\",\"p\":0}]', 1),
(72, 67, '我的下属', '', 126, 127, '[{\"r\":\"departments\\/mystaffs\",\"p\":0}]', 1),
(73, NULL, '订单管理', '', 129, 170, '[]', 1),
(74, 73, '新增订单', '', 130, 131, '[{\"r\":\"orders\\/add\",\"p\":0},{\"r\":\"commons\\/updatefile\",\"p\":0}]', 1),
(75, 73, '订单修改', '', 132, 133, '[{\"r\":\"orders\\/update\",\"p\":0},{\"r\":\"commons\\/updatefile\",\"p\":0}]', 1),
(76, 73, '删除订单', '', 134, 135, '[{\"r\":\"orders\\/delete\",\"p\":0}]', 1),
(77, 73, '我的订单', '', 136, 137, '[{\"r\":\"orders\\/myorders\",\"p\":0}]', 1),
(78, 73, '全部订单', '', 138, 139, '[{\"r\":\"orders\\/getorders\",\"p\":0}]', 1),
(79, 73, '部门订单', '', 140, 141, '[{\"r\":\"orders\\/departmentorders\",\"p\":0}]', 1),
(80, 73, '查看订单', '', 142, 143, '[{\"r\":\"orders\\/view\",\"p\":0}]', 1),
(81, 73, '订单查看+', '', 144, 145, '[{\"r\":\"orders\\/viewplus\",\"p\":0}]', 1),
(82, 73, '订单日志列表', '', 146, 147, '[{\"r\":\"orderactlogs\\/actlogs\",\"p\":0}]', 1),
(83, 73, '订单财务确认', 'orderUpdateFinance', 148, 149, '[{\"r\":\"orders\\/updatefinance\",\"p\":0}]', 1),
(84, NULL, '产品管理', '', 171, 200, '[]', 1),
(85, 84, '新增产品分类', '', 172, 173, '[{\"r\":\"productcategories\\/add\",\"p\":0}]', 1),
(86, 84, '修改产品分类', '', 174, 175, '[{\"r\":\"productcategories\\/update\",\"p\":0},{\"r\":\"productcategories\\/view\",\"p\":0}]', 1),
(87, 84, '获取产品分类', '', 176, 177, '[{\"r\":\"productcategories\\/getlist\",\"p\":0},{\"r\":\"productcategories\\/getkeypath\",\"p\":0}]', 1),
(88, 84, '移动产品分类', '', 178, 179, '[{\"r\":\"productcategories\\/move\",\"p\":0}]', 1),
(89, 84, '删除产品分类', '', 180, 181, '[{\"r\":\"productcategories\\/delete\",\"p\":0}]', 1),
(90, 84, '查看产品分类', '', 182, 183, '[{\"r\":\"productcategories\\/view\",\"p\":0}]', 1),
(91, 84, '新增产品', '', 184, 185, '[{\"r\":\"products\\/add\",\"p\":0}]', 1),
(92, 84, '获取产品', '', 186, 187, '[{\"r\":\"products\\/index\",\"p\":0},{\"r\":\"products\\/index\\/limit\",\"p\":0}]', 1),
(93, 84, '设置产品状态', '', 188, 189, '[{\"r\":\"products\\/setstatus\",\"p\":0}]', 1),
(94, 84, '删除产品', '', 190, 191, '[{\"r\":\"products\\/delete\",\"p\":0}]', 1),
(95, 84, '修改产品信息', '', 192, 193, '[{\"r\":\"products\\/update\",\"p\":0},{\"r\":\"products\\/view\",\"p\":0}]', 1),
(96, 84, '查看产品信息', '', 194, 195, '[{\"r\":\"products\\/view\",\"p\":0}]', 1),
(97, NULL, '统计', '', 201, 214, '[]', 1),
(98, 97, '统计所有客户', '', 202, 203, '[{\"r\":\"customerpools\\/statisticall\",\"p\":0}]', 1),
(99, 97, '统计所有订单', '', 204, 205, '[{\"r\":\"orders\\/statisticall\",\"p\":0}]', 1),
(100, 97, '统计部门客户', '', 206, 207, '[{\"r\":\"customerpools\\/statisticdepartment\",\"p\":0}]', 1),
(101, 97, '统计部门订单', '', 208, 209, '[{\"r\":\"orders\\/statisticdepartment\",\"p\":0}]', 1),
(102, 97, '统计我的客户', '', 210, 211, '[{\"r\":\"customerpools\\/statisticmine\",\"p\":0}]', 1),
(103, 97, '统计我的订单', '', 212, 213, '[{\"r\":\"orders\\/statisticmine\",\"p\":0}]', 1),
(104, 10, '菜单设置', '', 22, 23, '[{\"r\":\"sysmenus\\/add\",\"p\":0},{\"r\":\"sysmenus\\/update\",\"p\":0},{\"r\":\"sysmenus\\/getlist\",\"p\":0},{\"r\":\"sysmenus\\/getmenu\",\"p\":0},{\"r\":\"sysmenus\\/getkeypath\",\"p\":0},{\"r\":\"sysmenus\\/move\",\"p\":0},{\"r\":\"sysmenus\\/delete\",\"p\":0},{\"r\":\"sysmenus\\/view\",\"p\":0}]', 1),
(105, 48, '释放已过期客户', '', 112, 113, '[{\"r\":\"customerpools\\/gettimeoutcustomers\",\"p\":0}]', 1),
(106, 73, '修改成本价', 'orderUpdateCost', 150, 151, '[{\"r\":\"orders\\/updatecost\",\"p\":0}]', 1),
(107, 73, '初审', 'orderCheckStatus1', 152, 153, '[{\"r\":\"orders\\/updatestatus\\/1\",\"p\":0}]', 1),
(108, 73, '复审', 'orderCheckStatus2', 154, 155, '[{\"r\":\"orders\\/updatestatus\\/2\",\"p\":0}]', 1),
(109, 73, '审核不通过', 'orderCheckStatusFail', 156, 157, '[{\"r\":\"orders\\/updatestatus\\/-1\",\"p\":0}]', 1),
(110, 73, '查看审核记录', '', 158, 159, '[{\"r\":\"orderactlogs\\/checklogs\",\"p\":0}]', 1),
(111, 84, '产品库', '', 196, 197, '[{\"r\":\"products\\/index\\/limit\",\"p\":0}]', 1),
(112, 84, '产品查看', '', 198, 199, '[{\"r\":\"products\\/view\\/limit\",\"p\":0}]', 1),
(113, 73, '导出订单', '', 160, 161, '[{\"r\":\"orders\\/getexcel\",\"p\":0}]', 1),
(114, 48, '删除价值客户', '', 114, 115, '[{\"r\":\"customerpools\\/delete\",\"p\":0}]', 1),
(115, 73, '我的订单产品', '', 162, 163, '[{\"r\":\"orderproducts\\/getlist\\/user\",\"p\":0}]', 1),
(116, 73, '部门订单产品', '', 164, 165, '[{\"r\":\"orderproducts\\/getlist\\/department\",\"p\":0}]', 1),
(117, 73, '全部订单产品', '', 166, 167, '[{\"r\":\"orderproducts\\/getlist\\/admin\",\"p\":0}]', 1),
(118, 73, '导出部门订单', '', 168, 169, '[{\"r\":\"orders\\/getexcel\\/director\",\"p\":0}]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_aros`
--

CREATE TABLE `admin_aros` (
  `id` int(11) NOT NULL,
  `alias` varchar(20) NOT NULL COMMENT '用户组名',
  `allowed` text COMMENT '可访问的资源',
  `denied` text COMMENT '禁止访问的资源'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户组权限';

--
-- Dumping data for table `admin_aros`
--

INSERT INTO `admin_aros` (`id`, `alias`, `allowed`, `denied`) VALUES
(1, '超级管理员', '[3,15,16,17,18,19,20,24,1,21,104,10,23,25,26,27,28,22,29,31,32,33,34,35,30,37,38,39,40,41,42,43,44,45,46,47,36,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,105,114,48,68,69,70,71,72,67,74,75,76,77,78,79,80,81,82,83,106,107,108,109,110,113,115,116,117,118,73,85,86,87,88,89,90,91,92,93,94,95,96,111,112,84,98,99,100,101,102,103,97]', '[]'),
(4, '游客', '[18,1,105,48]', NULL),
(5, '产品主管', '[3,15,16,17,18,19,20,24,1,29,30,37,38,39,40,41,42,43,44,45,46,47,36,52,60,61,62,63,64,65,66,105,114,48,68,69,70,71,72,67,74,75,76,77,78,80,81,82,106,108,109,110,113,117,115,116,73,85,86,87,88,89,90,91,92,93,94,95,96,111,112,84,98,99,102,103,97]', NULL),
(6, '销售主管', '[17,18,1,29,30,39,43,47,36,49,50,51,53,54,55,56,57,58,59,66,48,72,67,74,75,76,77,79,80,110,115,116,118,73,87,90,92,96,111,112,84,100,101,102,103,97]', NULL),
(7, '销售专员', '[17,18,1,29,30,39,43,36,49,50,53,54,55,56,57,58,59,66,48,74,75,76,77,80,110,113,115,73,87,92,111,112,84,102,103,97]', NULL),
(8, '财务', '[17,18,1,29,30,78,81,83,106,108,109,110,113,117,73,87,92,84,102,103,97]', NULL),
(9, '产品专员', '[3,15,16,17,18,19,20,24,1,29,30,37,38,39,40,41,42,43,44,45,46,47,36,52,64,65,66,48,68,69,70,71,72,67,78,81,82,106,107,109,110,113,117,73,85,86,87,88,89,90,91,92,93,94,95,96,111,112,84,98,99,100,101,102,103,97]', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_groups`
--

CREATE TABLE `admin_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `admin_aro_id` int(10) UNSIGNED NOT NULL COMMENT '用户组id',
  `begin_time` int(11) DEFAULT NULL COMMENT '会员开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '会员结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户组';

--
-- Dumping data for table `admin_groups`
--

INSERT INTO `admin_groups` (`id`, `admin_id`, `admin_aro_id`, `begin_time`, `end_time`) VALUES
(27, 10, 5, NULL, NULL),
(29, 12, 9, NULL, NULL),
(31, 14, 7, NULL, NULL),
(33, 16, 7, NULL, NULL),
(34, 17, 7, NULL, NULL),
(35, 18, 7, NULL, NULL),
(37, 20, 7, NULL, NULL),
(38, 21, 7, NULL, NULL),
(40, 23, 6, NULL, NULL),
(41, 22, 7, NULL, NULL),
(44, 26, 7, NULL, NULL),
(45, 27, 7, NULL, NULL),
(47, 29, 7, NULL, NULL),
(50, 32, 7, NULL, NULL),
(55, 37, 8, NULL, NULL),
(56, 38, 8, NULL, NULL),
(61, 42, 8, NULL, NULL),
(62, 43, 7, NULL, NULL),
(64, 45, 9, NULL, NULL),
(65, 13, 9, NULL, NULL),
(67, 47, 6, NULL, NULL),
(68, 47, 7, NULL, NULL),
(69, 48, 7, NULL, NULL),
(71, 50, 9, NULL, NULL),
(72, 50, 5, NULL, NULL),
(73, 51, 7, NULL, NULL),
(74, 15, 7, NULL, NULL),
(75, 15, 6, NULL, NULL),
(76, 52, 9, NULL, NULL),
(77, 53, 9, NULL, NULL),
(78, 54, 9, NULL, NULL),
(82, 56, 6, NULL, NULL),
(84, 57, 9, NULL, NULL),
(85, 58, 8, NULL, NULL),
(86, 34, 7, NULL, NULL),
(87, 30, 7, NULL, NULL),
(89, 55, 7, NULL, NULL),
(90, 4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL COMMENT '合同名',
  `content` varchar(200) DEFAULT NULL COMMENT '合同介绍',
  `file_url` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，0-不可用，1-可用',
  `category_id` int(11) NOT NULL,
  `add_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='合同';

-- --------------------------------------------------------

--
-- Table structure for table `contract_categories`
--

CREATE TABLE `contract_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-不可用，1-可用',
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `wechat` char(30) NOT NULL COMMENT '微信号',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `gender` tinyint(4) DEFAULT '2' COMMENT '性别0-女，1-男，2-未知',
  `area` varchar(100) DEFAULT NULL COMMENT '所在地区',
  `company` varchar(100) DEFAULT NULL COMMENT '公司名',
  `department` varchar(50) DEFAULT NULL COMMENT '部门名',
  `brand` varchar(20) DEFAULT NULL COMMENT '品牌',
  `memo` varchar(200) DEFAULT NULL COMMENT '备注',
  `level` varchar(5) DEFAULT NULL COMMENT '意向登记',
  `is_bl_member` tinyint(4) DEFAULT NULL COMMENT '是否白鹿会0-否，1-是',
  `saler_id` int(11) DEFAULT NULL COMMENT '所属销售',
  `locked_by_user` varchar(30) DEFAULT NULL COMMENT '锁定人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_buy` int(11) DEFAULT NULL COMMENT '最后购买时间',
  `order_count` int(11) DEFAULT '0' COMMENT '下单数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

-- --------------------------------------------------------

--
-- Table structure for table `customer_act_logs`
--

CREATE TABLE `customer_act_logs` (
  `id` int(11) NOT NULL,
  `customer_pool_id` int(11) NOT NULL COMMENT '客户池中的id',
  `customer_wechat` varchar(30) NOT NULL COMMENT '客户微信号',
  `act` varchar(10) NOT NULL COMMENT '操作行为',
  `act_user` varchar(20) DEFAULT NULL COMMENT '操作人',
  `act_user_id` int(11) NOT NULL COMMENT '操作人id',
  `add_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客户池客户操作日志';

-- --------------------------------------------------------

--
-- Table structure for table `customer_pools`
--

CREATE TABLE `customer_pools` (
  `id` int(11) NOT NULL,
  `wechat` char(30) NOT NULL COMMENT '微信号',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `gender` tinyint(4) DEFAULT '2' COMMENT '性别0-女，1-男，2-未知',
  `area` varchar(100) DEFAULT NULL COMMENT '所在地区',
  `company` varchar(100) DEFAULT NULL COMMENT '公司名',
  `department` varchar(50) DEFAULT NULL COMMENT '部门名',
  `brand` varchar(20) DEFAULT NULL COMMENT '品牌',
  `memo` varchar(200) DEFAULT NULL COMMENT '备注',
  `is_bl_member` tinyint(4) DEFAULT NULL COMMENT '是否白鹿会0-否，1-是',
  `locked_by_user_id` int(11) DEFAULT '0' COMMENT '所属销售id，如果为0，则没有所属',
  `locked_by_user` varchar(30) DEFAULT NULL COMMENT '锁定人名',
  `locked_time` int(11) DEFAULT NULL COMMENT '锁定时间',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_buy` int(11) DEFAULT NULL COMMENT '最后购买时间',
  `order_count` int(11) DEFAULT '0' COMMENT '下单数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

-- --------------------------------------------------------

--
-- Table structure for table `customer_pool_recycles`
--

CREATE TABLE `customer_pool_recycles` (
  `id` int(11) NOT NULL,
  `wechat` char(30) NOT NULL COMMENT '微信号',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `gender` tinyint(4) DEFAULT '2' COMMENT '性别0-女，1-男，2-未知',
  `area` varchar(20) DEFAULT NULL COMMENT '所在地区',
  `company` varchar(30) DEFAULT NULL COMMENT '公司名',
  `department` varchar(30) DEFAULT NULL COMMENT '部门名',
  `brand` varchar(20) DEFAULT NULL COMMENT '品牌',
  `memo` varchar(200) DEFAULT NULL COMMENT '备注',
  `is_bl_member` tinyint(4) DEFAULT NULL COMMENT '是否白鹿会0-否，1-是',
  `locked_by_user_id` int(11) DEFAULT '0' COMMENT '所属销售id，如果为0，则没有所属',
  `locked_by_user` varchar(30) DEFAULT NULL COMMENT '锁定人名',
  `locked_time` int(11) DEFAULT NULL COMMENT '锁定时间',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_buy` int(11) DEFAULT NULL COMMENT '最后购买时间',
  `order_count` int(11) DEFAULT '0' COMMENT '下单数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

-- --------------------------------------------------------

--
-- Table structure for table `customer_sale_logs`
--

CREATE TABLE `customer_sale_logs` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `saler_id` int(11) NOT NULL COMMENT '销售id',
  `content` varchar(255) NOT NULL COMMENT '日志',
  `timestamp` int(11) NOT NULL COMMENT '日志录入时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客户销售日志';

--
-- Dumping data for table `customer_sale_logs`
--

INSERT INTO `customer_sale_logs` (`id`, `customer_id`, `saler_id`, `content`, `timestamp`) VALUES
(3, 7, 4, '懂懂懂', 1659657600),
(4, 7, 4, '来啊来啊', 1659744000),
(5, 10, 4, 'jint ;ia', 1661817600),
(6, 10, 4, 'fsdfsdf', 1661904000),
(7, 14, 7, 'sdfdsfdsfdsf', 1662336000),
(8, 14, 7, 'sdfsdfdsfdf', 1662336000),
(9, 29, 20, '咨询明信片：价格，回评率，具体操作流程，妥投率', 1662422400),
(10, 225, 25, '站外课客户，咨询美国站明信片详情', 1664294400),
(11, 159, 25, '已走财务流程5人团报，等支付水单截图，需要开发票', 1664294400),
(12, 241, 25, '在走明信片合同，先发5000金额的', 1664294400),
(13, 241, 25, '已签合同，等待支付收款单', 1664467200),
(14, 240, 25, '最终确认安排2人参加选品课，已发参课信息，已开具收据去安排财务走付款流程，说这周内付款', 1666886400),
(15, 296, 25, '说需要内部沟通下费用问题，今日跟进进度，暂时还没回复，明天继续询问下商量的进度', 1666886400),
(16, 324, 29, '已经引导购买了优惠卷，杭州大课参加完之后确定要不要报名', 1667318400),
(17, 294, 27, '明信片反馈图已经发给客户，等发出后跟进效果，在跟客户做一些专业分析，促成下次成交', 1667145600),
(18, 254, 27, '美国站点店铺已经付款，合同还没签，还需要我们售后帮忙绑定收款卡和信用卡完成交付，客户最近都非常忙，有回复说忙完再处理', 1667232000),
(19, 231, 27, '客户上完课后有帮客户邮寄发票，但是问课程反馈后客户就不回复消息了', 1664380800),
(20, 330, 25, '老客户转介绍过来的，想做信件直邮，是想先了解下，有发了问了下，然后发了文档，晚上或者明天再跟进下', 1667318400),
(21, 332, 25, '报名站外课，也是上海司顺的同事，需要晚几天确认下', 1667491200),
(22, 345, 25, '老客户，在跟进操盘手课程，等付款', 1668614400),
(23, 231, 27, '这个客户对PPC课程评价很高，觉得老师的方法很适合自己，又报了下一场的复训', 1668096000),
(24, 382, 25, '转介绍过来的，有做了几年的亚马逊，想多开一个新店铺，主要是做美国站，没计划做欧洲站，价格和账号情况已介绍清楚，考虑是年前或者年后付款购买', 1670428800),
(25, 384, 25, '咨询北美的精品店铺，主要是价格上觉得贵，已说明账号贵的原因，说考虑考虑', 1670428800),
(26, 387, 27, '根据上次课程反馈，申请参加4月14--16日的复训', 1681056000),
(27, 658, 15, '的hi䦹', 1686758400),
(28, 720, 47, '测试；电话处联系，客户在外地出差，预计8月20日回来。', 1692028800),
(31, 657, 15, '跟进', 1692892800),
(32, 737, 48, '寄合同', 1693152000);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL COMMENT '经理的user_id',
  `staff_id` int(11) NOT NULL COMMENT '员工user_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='部门成员表';

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `manager_id`, `staff_id`) VALUES
(9, 4, 4),
(10, 4, 3),
(19, 23, 22),
(20, 23, 21),
(21, 23, 20),
(22, 23, 19),
(23, 23, 18),
(24, 23, 17),
(25, 23, 16),
(26, 23, 15),
(27, 23, 14),
(33, 10, 13),
(34, 10, 12),
(35, 30, 34),
(36, 30, 33),
(38, 30, 31),
(40, 30, 21),
(43, 37, 42),
(44, 10, 41),
(45, 10, 45),
(46, 46, 40),
(47, 46, 43),
(48, 46, 32),
(49, 46, 20),
(50, 46, 19),
(51, 46, 18),
(52, 46, 17),
(53, 46, 16),
(54, 46, 15),
(55, 46, 14),
(56, 47, 51),
(57, 47, 48),
(58, 47, 29),
(59, 47, 26),
(60, 15, 43),
(61, 15, 32),
(62, 15, 20),
(63, 15, 17),
(64, 15, 16),
(65, 15, 14),
(66, 15, 18),
(67, 10, 54),
(68, 10, 53),
(69, 10, 52),
(70, 10, 50),
(71, 30, 55),
(72, 56, 55),
(73, 56, 34),
(74, 56, 30),
(75, 56, 21);

-- --------------------------------------------------------

--
-- Table structure for table `hard_configs`
--

CREATE TABLE `hard_configs` (
  `id` int(11) NOT NULL,
  `alias` char(20) NOT NULL,
  `value` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统必要配置';

--
-- Dumping data for table `hard_configs`
--

INSERT INTO `hard_configs` (`id`, `alias`, `value`) VALUES
(1, 'admin_control', '{\"guestGroup\":4,\"blockTime\":1646064000,\"loginDeny\":0,\"userNum\":100,\"loginErrorNum\":5,\"loginLockTime\":60,\"usingTime\":50,\"lockedDays\":60}');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_wechat` varchar(30) NOT NULL COMMENT '客户微信号',
  `customer_pool_id` int(11) NOT NULL COMMENT '客户池中的id',
  `product_id` int(11) DEFAULT NULL COMMENT '商品id',
  `product_category_id` int(11) DEFAULT NULL COMMENT '商品分类id',
  `price` int(11) DEFAULT '0' COMMENT '销售价格',
  `cost` int(11) DEFAULT '0' COMMENT '成本',
  `num` int(11) DEFAULT '0' COMMENT '购买数量',
  `order_time` int(11) DEFAULT NULL COMMENT '订单时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '付款时间',
  `contract_url` varchar(255) DEFAULT NULL COMMENT '合同地址',
  `pay_proof_url` text COMMENT '支付凭证地址',
  `memo` varchar(255) DEFAULT NULL COMMENT '订单说明',
  `saler_id` int(11) NOT NULL COMMENT '销售id',
  `refund_status` tinyint(4) UNSIGNED DEFAULT '0' COMMENT '是否有退款，1-有，0-无',
  `refund` int(11) DEFAULT '0' COMMENT '退款金额',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态，0-未确认，1-已确认',
  `add_time` int(11) NOT NULL COMMENT '订单添加时间',
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `product_category` varchar(30) DEFAULT NULL COMMENT '商品分类名',
  `product_name` varchar(30) DEFAULT NULL COMMENT '销售产品名',
  `customer_name` varchar(20) DEFAULT NULL COMMENT '客户姓名',
  `saler_name` varchar(20) DEFAULT NULL COMMENT '销售姓名',
  `check_status` tinyint(4) DEFAULT '0' COMMENT '审核状态-1，未通过，0-未审核，1-初审过，2-复审过',
  `order_type` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '订单类型，1-全款，0-定金',
  `settle_time` int(11) DEFAULT NULL,
  `settle_status` tinyint(3) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单';

-- --------------------------------------------------------

--
-- Table structure for table `order_act_logs`
--

CREATE TABLE `order_act_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT '订单中的id',
  `order_no` varchar(30) NOT NULL COMMENT '订单号',
  `act` varchar(10) NOT NULL COMMENT '操作行为',
  `act_user` varchar(20) NOT NULL COMMENT '操作人',
  `act_user_id` int(11) NOT NULL COMMENT '操作人id',
  `add_time` int(11) NOT NULL,
  `memo` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='客户池客户操作日志';

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL COMMENT '商品名',
  `order_id` int(11) NOT NULL,
  `total` int(11) DEFAULT '0' COMMENT '总价',
  `cost` int(11) DEFAULT '0' COMMENT '成本',
  `num` int(11) DEFAULT '0' COMMENT '数量',
  `category_name` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单产品';

-- --------------------------------------------------------

--
-- Table structure for table `order_recycles`
--

CREATE TABLE `order_recycles` (
  `id` int(11) NOT NULL,
  `customer_wechat` varchar(30) NOT NULL COMMENT '客户微信号',
  `customer_pool_id` int(11) NOT NULL COMMENT '客户池中的id',
  `product_id` int(11) DEFAULT NULL COMMENT '商品id',
  `product_category_id` int(11) DEFAULT NULL COMMENT '商品分类id',
  `price` int(11) DEFAULT '0' COMMENT '销售价格',
  `cost` int(11) DEFAULT '0' COMMENT '成本',
  `num` int(11) DEFAULT '0' COMMENT '购买数量',
  `order_time` int(11) DEFAULT NULL COMMENT '订单时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '付款时间',
  `contract_url` varchar(255) DEFAULT NULL COMMENT '合同地址',
  `pay_proof_url` varchar(255) DEFAULT NULL COMMENT '支付凭证地址',
  `memo` varchar(255) DEFAULT NULL COMMENT '订单说明',
  `saler_id` int(11) NOT NULL COMMENT '销售id',
  `refund_status` tinyint(4) DEFAULT NULL COMMENT '是否有退款，1-有，其他无',
  `refund` int(11) DEFAULT '0' COMMENT '退款金额',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态，0-未确认，1-已确认',
  `add_time` int(11) NOT NULL COMMENT '订单添加时间',
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `product_category` int(11) DEFAULT NULL COMMENT '商品分类名',
  `product_name` varchar(30) DEFAULT NULL COMMENT '销售产品名',
  `customer_name` varchar(20) DEFAULT NULL COMMENT '客户姓名',
  `saler_name` varchar(20) DEFAULT NULL COMMENT '销售姓名',
  `settle_time` int(11) DEFAULT NULL,
  `settle_status` tinyint(3) UNSIGNED DEFAULT '0',
  `order_type` tinyint(3) UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单';

--
-- Dumping data for table `order_recycles`
--

INSERT INTO `order_recycles` (`id`, `customer_wechat`, `customer_pool_id`, `product_id`, `product_category_id`, `price`, `cost`, `num`, `order_time`, `pay_time`, `contract_url`, `pay_proof_url`, `memo`, `saler_id`, `refund_status`, `refund`, `pay_status`, `add_time`, `order_no`, `product_category`, `product_name`, `customer_name`, `saler_name`, `settle_time`, `settle_status`, `order_type`) VALUES
(25, 'real_moss', 25, 2, 33, 88800, 30000, 10, 1660089600, 1660608000, NULL, NULL, NULL, 4, 0, 0, 0, 1660190367, '2022081137231', NULL, '美国卡片1', 'MOSS1', '唐的雾', NULL, 0, 1),
(30, 'real_moss', 25, 2, 33, 1000, 300, 10, 1660262400, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660270894, '2022081280944', NULL, '美国卡片1', 'MOSS2', '唐的雾', NULL, 0, 1),
(31, 'real_moss', 25, 2, 33, 1999, 3000, 100, 1660262400, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660271555, '2022081279224', NULL, '美国卡片1', 'MOSS2', '唐的雾', NULL, 0, 1),
(32, 'real_moss', 25, 2, 33, 666600, 198000, 66, 1659744000, 1660608000, '22\\08\\15\\22081503214763609.doc', '22\\08\\15\\22081503215153116.png', '123123', 4, 0, 0, 0, 1660277024, '2022081213383', NULL, '美国卡片1', 'MOSS2', '唐的雾', NULL, 0, 1),
(33, 'dave', 26, 2, 33, 100000, 3000, 1, 1660780800, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660788998, '202208181656452', NULL, '美国卡片1', 'tang', '唐的雾', NULL, 0, 1),
(34, 'bbq', 27, 3, 39, 100000, 80000, 1, 1660867200, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660879403, '202208192395963', NULL, '侃侃大学学习', '巴比奇', '唐的雾', NULL, 0, 1),
(35, 'real_moss', 25, 2, 33, 99900, 27000, 9, 1660867200, NULL, NULL, NULL, NULL, 4, 0, 0, 0, 1660881601, '202208190068882', NULL, '美国卡片1', 'MOSS2', '唐的雾', NULL, 0, 1),
(36, 'bbq', 27, 2, 33, 1000000, 300000, 10, 1661817600, 1661817600, '22\\08\\30\\22083008392588206.docx', '22\\08\\30\\22083008393193408.png', 'asdsad', 4, 1, 0, 0, 1661848639, '202208303738128', NULL, '美国卡片1', '巴比奇', '唐的雾', NULL, 0, 1),
(37, 'real_moss', 25, 2, 33, 111100, 33000, 11, 1661904000, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1661916145, '202208312283246', NULL, '美国卡片1', 'MOSS2', '唐的雾', NULL, 0, 1),
(38, 'bubuy', 28, 3, 39, 111100, 990000, 11, 1661904000, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1661917824, '202208315041647', NULL, '侃侃大学学习', 'Achao', '唐的雾', NULL, 0, 1),
(39, 'bubuy', 28, 3, 39, 100000, 900000, 10, 1661904000, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1661918115, '202208315534597', NULL, '侃侃大学学习', 'Achao', '唐的雾', NULL, 0, 1),
(40, 'Achao', 29, 3, 39, 1000000, 9000000, 100, 1661904000, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1661918146, '202208315501772', NULL, '侃侃大学学习', 'tzc', '唐的雾', NULL, 0, 1),
(43, 'Happy_baicai', 32, 4, 56, 1000000, 600000, 1, 1659398400, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662435201, '202209063347016', NULL, '全站点亚马逊精品店铺', '小张红', '傅晶', NULL, 0, 1),
(46, 'arex1230', 35, 5, 56, 1200000, 700000, 2, 1659398400, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662436839, '202209060009558', NULL, '全站点亚马逊普通店铺', 'Alex', '傅晶', NULL, 0, 1),
(52, 'WanGekan', 41, 15, 61, 600000, 80000, 1, 1659916800, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662450440, '202209064765251', NULL, '英国公司代注册', '林志猛', '傅晶', NULL, 0, 1),
(54, 'Ztjh0223', 43, 19, 62, 1000000, 650000, 1, 1660262400, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662451763, '202209060936817', NULL, '护照法人+配合注册外国公司+店铺', '张志途', '傅晶', NULL, 0, 1),
(55, 'q651279666', 44, 14, 61, 500000, 120000, 1, 1659657600, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662463461, '202209062479209', NULL, '中国公司次年续约（记账申报+年审）', '蓝柏良', '傅晶', NULL, 0, 1),
(56, 'invete', 45, 13, 61, 250000, 100000, 1, 1660262400, NULL, NULL, NULL, NULL, 14, 0, 0, 0, 1662463756, '202209062958787', NULL, '中国公司代注册（公司+申报+年审）', 'invete', '傅晶', NULL, 0, 1),
(63, 'qq396375202', 49, 26, 58, 1545, 963, 321, 1662422400, NULL, NULL, NULL, '（61+221+40）*4.8', 16, NULL, 0, 0, 1662513829, '202209072387181', NULL, '美国明信片邮寄印刷', '愣在那里', '周雨阳', NULL, 0, 1),
(67, 'simonlion54', 46, 40, 53, 31, 63, 63, 1661990400, NULL, NULL, NULL, NULL, 14, NULL, 0, 0, 1662514649, '202209073773475', NULL, 'FBA导地址服务', '西蒙斯', '傅晶', NULL, 0, 1),
(69, 'qq396375202', 49, 26, 53, 1713, 3, 1, 1661126400, NULL, NULL, NULL, NULL, 16, NULL, 0, 0, 1662514953, '202209074272248', NULL, '美国明信片邮寄印刷', '愣在那里', '周雨阳', NULL, 0, 1),
(72, 'caicaili132', 54, 40, 53, 35200, 58700, 587, 1660003200, NULL, NULL, '', NULL, 20, 0, 0, 0, 1662515498, '202209075181878', NULL, 'FBA导地址服务', '不知秋', '李双鹏', NULL, 0, 1),
(73, 'caicaili132', 54, 26, 53, 249000, 184260, 498, 1660233600, NULL, NULL, '[\"22\\/10\\/12\\/22101214544695839.jpg\"]', '单价：5元/张\n数量：498张\n', 20, 0, 0, 0, 1662515887, '202209075858533', NULL, '美国明信片邮寄印刷', '不知秋', '李双鹏', NULL, 0, 1),
(74, 'xue54410', 55, 26, 53, 27900, 11700, 39, 1658448000, NULL, NULL, NULL, '导地址：168*0.5=84元\n明信片：39*5=195元\n合计：279元', 20, 0, 0, 0, 1662516404, '202209070648890', NULL, '美国明信片邮寄印刷', '薛奕林', '李双鹏', NULL, 0, 1),
(75, 'xiaobao4928', 56, 26, 53, 141100, 69300, 231, 1656288000, NULL, NULL, NULL, '导地址：256*1-256元\n明信片：231*5=1155元\n合计：1411元', 20, 0, 0, 0, 1662517722, '202209072839270', NULL, '美国明信片邮寄印刷', 'Bowen', '李双鹏', NULL, 0, 1),
(77, 'bbq211', 58, 26, 53, 119400, 57900, 193, 1657756800, NULL, NULL, NULL, '导地址：229*1=229元\n明信片：193*5=965元\n合计：1194元', 20, 0, 0, 0, 1662518086, '202209073448951', NULL, '美国明信片邮寄印刷', '🦋BBQ🦋', '李双鹏', NULL, 0, 1),
(79, 'Zz630-91', 60, 27, 53, 252900, 157000, 314, 1658966400, NULL, NULL, NULL, '导地址：662*0.5=331元\n明信片：314*7=2198元\n合计：2529元', 20, 0, 0, 0, 1662518331, '202209073800884', NULL, '美国信件印刷邮寄', 'yz', '李双鹏', NULL, 0, 1),
(83, 'L2303253910', 64, 26, 53, 31200, 15600, 52, 1658966400, NULL, NULL, NULL, '向日葵：52元\n明信片：52*5=260元\n合计：312元', 20, 0, 0, 0, 1662519615, '202209070011860', NULL, '美国明信片邮寄印刷', 'LiK-', '李双鹏', NULL, 0, 1),
(84, 'wxid_3o6jv8k988io22', 65, 26, 53, 144500, 78600, 262, 1658275200, NULL, NULL, NULL, '导地址：271*0.5=135.5元\n明信片：262*5=1310元\n合计：1445.5元\n后期退款：7张*5元=35元\n实际业绩：1410.5元', 20, 0, 0, 0, 1662519948, '202209070592079', NULL, '美国明信片邮寄印刷', 'Dirani-Betty', '李双鹏', NULL, 0, 1),
(88, 'samuelzhang-SH', 68, 18, 62, 400000, 400000, 1, 1662480000, NULL, NULL, NULL, '法人翟学东 续费', 14, 0, 0, 0, 1662541283, '202209070105138', NULL, '国内法人资料+配合注册店铺', '张天籁', '傅晶', NULL, 0, 1),
(92, 'c3103103', 72, 30, 70, 1400000, 320000, 2, 1658880000, NULL, NULL, '[\"22\\/09\\/08\\/22090801024044122.jpg\"]', NULL, 20, 0, 0, 0, 1662599074, '202209080437575', NULL, 'ppc中高阶实战特训营', '刘梦婷 ', '李双鹏', NULL, 0, 1),
(93, 'real_moss', 25, 3, 47, 100, 950000, 1, 1662595200, NULL, NULL, '22/09/08/22090801433141762.png', NULL, 23, NULL, 0, 0, 1662601441, '202209084486955', NULL, '全站点亚马逊精品店铺（带英德VAT）', 'moss', '测试员', NULL, 0, 1),
(94, 'jsjfjfsj ', 73, 4, 56, 900000, 600000, 1, 1662595200, NULL, NULL, '22/09/08/22090801505187012.jpeg', NULL, 11, NULL, 0, 0, 1662601883, '202209085134116', NULL, '全站点亚马逊精品店铺', 'ydhsaahd', '颜小杰', NULL, 0, 1),
(103, 'makechun0895', 80, 40, 53, 128340, 77400, 2580, 1661788800, NULL, NULL, '', '导地址2580条', 14, 0, 0, 0, 1662688195, '202209094952343', NULL, 'FBA导地址服务', '马克纯', '傅晶', NULL, 0, 1),
(104, 'FYT88588', 81, 47, 75, 219900, 175920, 1, 1659542400, NULL, NULL, '[\"22\\/09\\/09\\/22090902010473290.png\"]', '线上购买', 14, 0, 0, 0, 1662688915, '202209090159684', NULL, '6个月套餐', 'FYT', '傅晶', NULL, 0, 1),
(106, 'toughmark', 83, 30, 70, 700000, 160000, 1, 1658188800, NULL, NULL, '[\"22\\/09\\/09\\/22090902034435378.jpg\"]', NULL, 14, 0, 0, 0, 1662689051, '202209090494284', NULL, 'ppc中高阶实战特训营', '胡涵', '傅晶', NULL, 0, 1),
(114, 'Ztjh0223', 43, 27, 59, 399500, 32330, 61, 1663027200, NULL, NULL, '22/09/13/22091309104662680.jpg', '导地址67条，寄47+14张', 14, 0, 0, 0, 1663060265, '202209131162253', NULL, '美国信件印刷邮寄', '张志途', '傅晶', NULL, 0, 1),
(147, 'qiunan', 112, 50, 53, 60000, 74000, 100, 1664208000, NULL, NULL, '22/09/27/22092711593181672.jpeg', NULL, 11, NULL, 0, 0, 1664251176, '202209275948507', NULL, '日本信件邮寄印刷', '秋楠', '颜小杰', NULL, 0, 1),
(148, 'ajjs', 113, 4, 47, 900000, 600000, 1, 1664208000, NULL, NULL, '22/09/27/22092715092052230.png', NULL, 11, NULL, 0, 0, 1664262563, '202209270946640', NULL, '全站点亚马逊精品店铺', '有NZJ', '颜小杰', NULL, 0, 1),
(166, 'wiwjds', 129, 4, 56, 1000000, 600000, 1, 1664208000, NULL, NULL, '22/09/27/22092717073342803.jpeg', NULL, 11, NULL, 0, 0, 1664269689, '202209270820330', NULL, '全站点亚马逊精品店铺', '李双鹏', '颜小杰', NULL, 0, 1),
(167, 'sjdkjfs', 130, 4, 56, 1000000, 600000, 1, 1664208000, NULL, NULL, '22/09/27/22092717085342979.jpeg', NULL, 11, NULL, 0, 0, 1664269858, '202209271061476', NULL, '全站点亚马逊精品店铺', '对于声控灯', '颜小杰', NULL, 0, 1),
(168, 'ld12897', 131, 26, 58, 120270, 370, 1, 1664121600, NULL, NULL, '[\"22\\/09\\/27\\/22092717364491720.jpg\"]', NULL, 25, 0, 0, 0, 1664271407, '202209273674728', NULL, '美国明信片邮寄印刷', '前方', '唐旭', NULL, 0, 1),
(170, 'kaiziyang123', 133, 26, 58, 1976820, 370, 1, 1663171200, NULL, NULL, '[\"22\\/09\\/27\\/22092717541552848.png\"]', NULL, 25, 0, 0, 0, 1664272819, '202209270084097', NULL, '美国明信片邮寄印刷', '杨凯', '唐旭', NULL, 0, 1),
(173, 'Justdoitkkf', 136, 28, 70, 548000, 239200, 1, 1661443200, NULL, NULL, '[\"22\\/09\\/27\\/22092718075580436.jpg\"]', NULL, 25, 0, 0, 0, 1664273320, '202209270865252', NULL, '品牌与海外营销', 'reliy', '唐旭', NULL, 0, 1),
(174, 'xx1330000', 137, 4, 56, 900000, 1800000, 3, 1659974400, NULL, NULL, '22/09/27/22092716551955981.jpg', NULL, 32, 0, 0, 0, 1664277954, '202209272541263', NULL, '全站点亚马逊精品店铺', '王军', '曾红铫', NULL, 0, 1),
(175, 'Jiamin_Huangc', 138, 26, 58, 393600, 242720, 656, 1664208000, NULL, NULL, NULL, NULL, 32, 0, 0, 0, 1664278394, '202209273310606', NULL, '美国明信片邮寄印刷', 'Eve.', '曾红铫', NULL, 0, 1),
(176, 'X975875031', 139, 49, 58, 485050, 377300, 539, 1663257600, NULL, NULL, '[\"22\\/09\\/27\\/22092719410728339.png\"]', '日本明信片 共539张', 32, 0, 0, 0, 1664278895, '202209274185219', NULL, '日本明信片印刷邮寄', '会员陈江涛的员工 ', '曾红铫', NULL, 0, 1),
(178, 'lqxiaomu', 141, 29, 70, 599900, 240000, 1, 1664208000, NULL, NULL, NULL, '想加入白鹿会，跟进中', 32, 0, 0, 0, 1664280601, '202209271036341', NULL, '亚马逊选品开发', '伊伊', '曾红铫', NULL, 0, 1),
(179, 'lin2022', 142, 30, 70, 2800000, 160000, 1, 1663776000, NULL, NULL, '22/09/27/22092720163250055.jpg', '入会会费', 32, 0, 0, 0, 1664281000, '202209271603432', NULL, 'ppc中高阶实战特训营', '黄林桉', '曾红铫', NULL, 0, 1),
(184, 'wxid_houinvs851vc11', 146, 26, 58, 322740, 370, 1, 1664121600, NULL, NULL, '[\"22\\/09\\/28\\/22092815360216826.jpg\"]', NULL, 25, 0, 0, 0, 1664350589, '202209283607682', NULL, '美国明信片邮寄印刷', '周互珍', '唐旭', NULL, 0, 1),
(203, 'jiarongyeah', 160, 53, 77, 0, 0, 0, 1658073600, NULL, NULL, '22/09/29/22092910233044328.jpg', '入会会费', 32, NULL, 0, 0, 1664418218, '202209292370999', NULL, '白鹿会会员入会', '刘嘉荣', '曾红铫', NULL, 0, 1),
(204, 'Jcary-XX', 161, 30, 70, 700000, 280000, 1, 1658764800, NULL, NULL, '22/09/29/22092910380326343.jpg', '老板-李莜 （以前是会员祁东的员工）', 32, NULL, 0, 0, 1664419115, '202209293856039', NULL, 'ppc中高阶实战特训营', 'Cary 姜凯悦 ', '曾红铫', NULL, 0, 1),
(244, 'yxx451292832', 178, 40, 53, 11370, 30, 1, 1664467200, NULL, NULL, '[\"22\\/09\\/30\\/22093011130135684.png\"]', '导地址', 25, 0, 0, 0, 1664507584, '202209301373194', NULL, 'FBA导地址服务', '余潇潇', '唐旭', NULL, 0, 1),
(245, 'wxid_houinvs851vc11', 146, 40, 53, 5430, 30, 1, 1664467200, NULL, NULL, '[\"22\\/09\\/30\\/22093016254615276.jpg\"]', NULL, 25, 0, 0, 0, 1664526347, '202209302526661', NULL, 'FBA导地址服务', '周互珍', '唐旭', NULL, 0, 1),
(246, 'yxx451292832', 178, 26, 53, 166800, 102860, 278, 1665072000, NULL, NULL, '[\"22\\/10\\/08\\/22100810103427100.jpg\"]', NULL, 25, 0, 0, 0, 1665195042, '202210081034968', NULL, '美国明信片邮寄印刷', '余潇潇', '唐旭', NULL, 0, 1),
(247, 'wxid_houinvs851vc11', 146, 26, 53, 20320, 370, 1, 1664294400, NULL, NULL, '22/10/08/22100810471269370.png', NULL, 25, 0, 0, 0, 1665197240, '202210084788011', NULL, '美国明信片邮寄印刷', '周互珍', '唐旭', NULL, 0, 1),
(248, 'zls107526', 179, 27, 53, 220200, 194510, 367, 1664812800, NULL, NULL, '[\"22\\/10\\/08\\/22100811235315990.jpg\"]', '系统上下单的客户', 26, 0, 0, 0, 1665199477, '202210082404861', NULL, '美国信件印刷邮寄', '1', '李健', NULL, 0, 1),
(251, 'mtf272905844', 100, 4, 56, 900000, 600000, 1, 1665331200, NULL, NULL, '22/10/10/22101010354348309.jpg', '北美入口，9.7日支付定金1000元，今日支付尾款8000元', 14, 0, 0, 0, 1665369376, '202210103687742', NULL, '全站点亚马逊精品店铺', '马腾飞', '傅晶', NULL, 0, 1),
(269, 'Minor_ LY-D666', 191, 30, 70, 748000, 160000, 1, 1666195200, NULL, NULL, '[\"22\\/10\\/21\\/22102110431421418.jpg\"]', NULL, 17, 0, 0, 0, 1666320243, '202210214471062', NULL, 'ppc中高阶实战特训营', '杜乐宇', '喻翔', NULL, 0, 1),
(294, 'Aiden11322', 212, 26, 53, 2042760, 370, 1, 1667145600, NULL, NULL, '[\"22\\/11\\/02\\/22110209510516361.png\"]', '业绩录入', 15, 0, 0, 0, 1667353966, '202211025202730', NULL, '美国明信片邮寄印刷', '叶远伟', '叶远伟', NULL, 1, 1),
(295, 'Aiden11322', 212, 27, 53, 2042760, 530, 1, 1667145600, NULL, NULL, '[\"22\\/11\\/02\\/22110210014189860.png\"]', NULL, 15, 0, 0, 0, 1667354513, '202211020167140', NULL, '美国信件印刷邮寄', '叶远伟', '叶远伟', NULL, 1, 1),
(316, 'Hiu19950314', 229, 4, 56, 900000, 600000, 1, 1667318400, NULL, '22/11/04/22110410230511969.pdf', '[\"22\\/11\\/04\\/22110410213174990.jpg\"]', NULL, 26, 0, 0, 0, 1667528601, '202211042338868', NULL, '全站点亚马逊精品店铺', '胡枝琪', '李健', 1667318400, 1, 1),
(360, 'wxid_zvg7p1o6oq3422', 259, 58, 75, 299900, 239920, 1, 1667491200, NULL, NULL, '[\"22\\/11\\/30\\/22113014122731583.jpg\"]', NULL, 20, 0, 0, 0, 1669788750, '202211301236016', NULL, '智慧卖家业绩录入', 'Mitsui', '李双鹏', NULL, 0, 1),
(383, '业绩录入1', 216, 58, 75, 2549122, 2039298, 11, 1669737600, NULL, NULL, '[\"22\\/12\\/01\\/22120113550384025.png\",\"22\\/12\\/01\\/22120113551315626.png\"]', NULL, 32, 0, 0, 0, 1669874178, '202212015632178', NULL, '智慧卖家业绩录入', '曾红铫', '曾红铫', 1669737600, 1, 1),
(399, 'Luo_jifeng008', 276, 53, 77, 2800000, 0, 1, 1668960000, NULL, NULL, '[\"22\\/12\\/08\\/22120811212484393.jpg\"]', NULL, 25, 0, 0, 0, 1670469732, '202212082213741', NULL, '白鹿会会员入会', '罗吉峰', '唐旭', NULL, 0, 1),
(405, 'real_moss', 25, 53, 77, 0, 0, 0, 1670947200, NULL, NULL, '[\"22\\/12\\/14\\/22121410463724819.png\"]', NULL, 4, 0, 0, 0, 1670985999, '202212144621710', NULL, '白鹿会会员入会', 'MOSS2', '唐的雾', 1670947200, 0, 1),
(434, 'zhouyuyang', 219, 57, 53, 8269110, 0, 1, 1672416000, NULL, NULL, '[\"23\\/01\\/03\\/23010316444429420.png\"]', NULL, 16, 0, 0, 0, 1672735496, '202301034449458', NULL, '欧美明信片业绩录入', '业绩录入', '周雨阳', 1672416000, 1, 1),
(449, 'wxid_9vpcg5f6glpt11', 303, 19, 62, 2000000, 1300000, 2, 1675008000, NULL, NULL, '[\"23\\/01\\/30\\/23013012023798841.jpg\"]', '2套护照法人资料', 14, 0, 0, 0, 1675051373, '202301300221081', NULL, '护照法人+配合注册外国公司+店铺', '郑昆杰', '傅晶', NULL, 0, 1),
(586, 'YONGYAN_720', 117, 28, 70, 649900, 259960, 1, 1678291200, NULL, NULL, '[\"23\\/03\\/09\\/23030916480178832.jpg\"]', NULL, 18, 0, 0, 0, 1678351692, '202303094850456', NULL, '品牌与海外营销', '刘勇言', '吴少波', 1678291200, 1, 1),
(612, 'zwk2010630', 399, 4, 47, 50000, 600000, 1, 1677600000, NULL, NULL, '[\"23\\/03\\/10\\/23031014014236727.jpg\"]', NULL, 32, 0, 0, 0, 1678428165, '202303100248835', NULL, '全站点亚马逊精品店铺', '赵文科', '曾红铫', NULL, 1, 0),
(617, 'xu578951588', 401, 28, 70, 649900, 259960, 2, 1678464000, NULL, NULL, '[\"23\\/03\\/11\\/23031116020845445.png\"]', NULL, 43, 0, 0, 0, 1678521777, '202303110279264', NULL, '品牌与海外营销', 'cinthia xu', '程静', NULL, 0, 1),
(694, 'Ada_S20228', 402, 30, 70, 749900, 149980, 1, 1678118400, NULL, NULL, '[\"23\\/04\\/03\\/23040316002958326.png\"]', NULL, 15, 0, 0, 0, 1680508831, '202304030013345', NULL, 'ppc中高阶实战特训营', 'Ada🍭', '叶远伟', NULL, 0, 1),
(696, 'XycMona2021', 447, 56, 47, 400000, 170000, 1, 1678118400, NULL, NULL, '[\"23\\/04\\/03\\/23040316044580743.jpg\"]', NULL, 15, 0, 0, 0, 1680509087, '202304030434685', NULL, '全站点亚马逊精品店铺第二年续费', 'Mona', '叶远伟', NULL, 0, 1),
(767, 'fj0103050788', 264, 58, 75, 792202, 633762, 1, 1682784000, NULL, NULL, '[\"23\\/05\\/04\\/23050414465837365.jpg\"]', NULL, 14, 0, 0, 0, 1683182820, '202305044732540', NULL, '智慧卖家业绩录入', '业绩录入', '傅晶', NULL, 0, 1),
(876, 'MUSCLE1314BOY', 544, 57, 53, 41000, 0, 100, 1686585600, NULL, NULL, '[\"23\\/06\\/13\\/23061317492434902.jpg\"]', NULL, 14, 0, 0, 0, 1686649857, '202306135028521', NULL, '欧美明信片业绩录入', '阿树', '傅晶', NULL, 0, 1),
(928, 'tony210908', 573, 57, 53, 421300, 0, 1, 1688313600, NULL, NULL, '[\"23\\/07\\/20\\/23072012145146144.jpg\"]', NULL, 26, 0, 0, 0, 1689826496, '202307201483437', NULL, '欧美明信片业绩录入', '杨', '李健', 1688313600, 1, 1),
(947, 'fortune_365', 576, 30, 70, 848000, 169600, 1, 1690992000, NULL, NULL, '[\"23\\/08\\/03\\/23080310162023407.jpg\"]', NULL, 14, 0, 0, 0, 1691028984, '202308031636442', NULL, 'ppc中高阶实战特训营', '王生', '傅晶', NULL, 0, 1),
(982, 'kankan201819', 223, 57, 53, 14548634, 0, 1, 1693411200, NULL, NULL, '[\"23\\/09\\/01\\/23090111260741336.png\"]', '八月份明信片业绩录入', 26, 0, 0, 0, 1693538778, '202309012695984', NULL, '欧美明信片业绩录入', '李健业绩录入', '李健', 1693411200, 1, 1),
(983, 'kankan201819', 223, 57, 53, 99000, 0, 1, 1693411200, NULL, NULL, '[\"23\\/09\\/01\\/23090111364745866.png\"]', '八月份线下明信片业绩', 26, 0, 0, 0, 1693539424, '202309013746818', NULL, '欧美明信片业绩录入', '李健业绩录入', '李健', 1693411200, 1, 1),
(994, '20606', 613, 63, 47, 21000000, 1500000, 5, 1694016000, NULL, NULL, '[\"23\\/09\\/07\\/23090715113582313.jpg\",\"23\\/09\\/07\\/23090715114913880.jpg\",\"23\\/09\\/07\\/23090715120747747.png\"]', NULL, 43, 0, 0, 0, 1694070732, '202309071264129', NULL, '英国店铺代注册', '18028778227', '程静', NULL, 0, 1),
(1008, 'AL15216076584', 627, 4, 47, 250000, 600000, 1, 1698163200, NULL, NULL, '[\"23\\/10\\/25\\/23102515315321510.jpg\"]', NULL, 43, 0, 0, 0, 1698219115, '202310253107663', NULL, '全站点亚马逊精品店铺', 'Aillen', '程静', NULL, 0, 1),
(1134, 'simone97fourever', 645, 4, 47, 1000000, 600000, 1, 1699977600, NULL, NULL, '[\"23\\/11\\/16\\/23111609111539099.jpg\"]', NULL, 14, 0, 0, 0, 1700097076, '202311161166897', NULL, '全站点亚马逊精品店铺', '季初阳', '傅晶', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL COMMENT '商品名称',
  `price` int(11) DEFAULT '0' COMMENT '指导价格',
  `cost` int(11) DEFAULT '0' COMMENT '成本',
  `category_id` int(11) DEFAULT NULL COMMENT '分类id',
  `status` tinyint(4) DEFAULT '1' COMMENT '0-不可用，1-可用',
  `memo` text COMMENT '产品介绍',
  `cost_type` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '成本计算方式，0-实价，1-比例	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品';

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `cost`, `category_id`, `status`, `memo`, `cost_type`) VALUES
(3, '全站点亚马逊精品店铺（带英德VAT）', 1500000, 950000, 56, 1, NULL, 0),
(4, '全站点亚马逊精品店铺', 1000000, 600000, 56, 1, NULL, 0),
(5, '全站点亚马逊普通店铺', 600000, 300000, 56, 1, '<p><br></p>', 0),
(11, '法国公司代注册（公司+VAT申报+年审）', 3400000, 1300000, 61, 1, '<p><br></p>', 0),
(12, '法国公司次年续费（申报+地址续期）', 1600000, 880000, 86, 1, '<p><br></p>', 0),
(13, '中国公司代注册（公司+申报+年审）', 200000, 100000, 61, 1, NULL, 0),
(19, '护照代持人资料', 1200000, 650000, 89, 1, '<p><br></p>', 0),
(20, '海外研究院', 300000, 0, 64, 1, NULL, 0),
(21, '广告研究院', 300000, 0, 64, 1, NULL, 0),
(22, '放量研究院', 300000, 0, 64, 1, NULL, 0),
(25, '侃侃大学', 300000, 0, 36, 1, NULL, 0),
(28, '品牌与海外营销', 598000, 4000, 70, 1, '<p><br></p>', 1),
(29, '亚马逊选品开发', 599900, 4000, 70, 1, '<p><br></p>', 1),
(30, 'ppc中高阶实战特训营', 799900, 2000, 70, 1, '<p><br></p>', 1),
(31, '爆款打造精品课', 9980000, 4990000, 70, 1, NULL, 0),
(49, '日本明信片印刷邮寄', 850, 700, 58, 1, NULL, 0),
(50, '日本信件邮寄印刷', 1000, 800, 59, 1, '<p><br></p>', 0),
(51, '亚马逊欧洲VC店铺代注册', 12000000, 6000000, 88, 1, '<p><br></p>', 0),
(52, '大咖爆款课', 0, 0, 77, 1, '<p><br></p>', 0),
(53, '白鹿会会员入会', 0, 0, 77, 1, '<p><br></p>', 0),
(54, 'shopify独立站实战营', 400000, 120000, 70, 1, '<p><br></p>', 0),
(55, '跨境电商绩效增长系统', 599900, 3000, 70, 1, '<p><br></p>', 1),
(56, '全站点亚马逊精品店铺第二年续费', 400000, 170000, 47, 1, '<p><br></p>', 0),
(57, '欧美明信片业绩录入', 0, 0, 53, 1, '', 0),
(58, '智慧卖家业绩录入', 0, 8000, 75, 1, '<p><br></p>', 1),
(59, '亚马逊操盘手特训营', 1980000, 4000, 81, 1, '<p><br></p>', 1),
(60, '亚马逊VC特训班', 6000000, 5000, 77, 1, '<p><br></p>', 1),
(61, '年终大课', 0, 0, 77, 1, '<p><br></p>', 0),
(64, '企业内训', 18000000, 5000, 70, 1, '<p><br></p>', 1),
(65, '亚马逊新人课', 299900, 0, 70, 1, '<p><br></p>', 0),
(66, '跨境电商《组织核能-降本增效》', 2980000, 4000, 70, 1, '<p><br></p>', 1),
(67, '定制英企店铺+英国vat', 4700000, 2100000, 57, 1, '<p><br></p>', 0),
(68, '定制英企店铺+标记', 4400000, 1800000, 57, 1, '<p><br></p>', 0),
(69, '定制英企店铺+英国vat+标记', 5200000, 2400000, 57, 1, '<p><br></p>', 0),
(70, '定制北爱店铺', 5700000, 2800000, 57, 1, '<p><br></p>', 0),
(71, '定制北爱店铺+英国vat', 6700000, 3400000, 57, 1, '<p><br></p>', 0),
(72, '定制北爱店铺+欧盟标记', 6200000, 3100000, 57, 1, '<p><br></p>', 0),
(73, '定制北爱店铺+英欧标记', 6700000, 3400000, 57, 1, '<p><br></p>', 0),
(74, '代注册欧盟本土店铺', 600000, 400000, 88, 1, '<p><br></p>', 0),
(75, '代注册英国本土店铺', 500000, 300000, 88, 1, '<p><br></p>', 0),
(76, '代注册中企亚马逊店铺', 250000, 100000, 88, 1, '<p><br></p>', 0),
(77, '大客户经理链接', 350000, 120000, 78, 1, '<p><br></p>', 0),
(78, '国内店铺/代持人资料次年续费', 400000, 200000, 86, 1, '<p><br></p>', 0),
(79, '国内代持人资料', 900000, 500000, 89, 1, '<p><br></p>', 0),
(80, '定制法企亚马逊店铺', 6000000, 2500000, 57, 1, '<p><br></p>', 0),
(81, '单站点小号', 250000, 200000, 56, 1, '<p><br></p>', 0),
(82, '定制法企店铺次年续费', 1900000, 1030000, 86, 1, '<p>包含公司地址+vat0申报+代持人费用</p>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-不可用，1-可用',
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `title`, `status`, `parent_id`, `lft`, `rght`) VALUES
(36, '线上课程', 1, NULL, 23, 28),
(39, '侃侃大学', 1, 36, 24, 25),
(47, '亚马逊店铺', 1, NULL, 5, 14),
(53, '明信片直邮营销业务', 1, NULL, 15, 22),
(56, '国内公司亚马逊店铺', 1, 47, 6, 7),
(57, '外企公司亚马逊店铺', 1, 47, 8, 9),
(58, '明信片印刷邮寄', 1, 53, 16, 17),
(59, '信件印刷邮寄', 1, 53, 18, 19),
(60, '导出FBA地址', 1, 53, 20, 21),
(61, '公司代注册', 1, NULL, 1, 2),
(64, '研究院视频', 1, 36, 26, 27),
(70, '线下课程', 1, NULL, 29, 48),
(71, '品牌与海外营销', 1, 70, 30, 31),
(72, '亚马逊选品开发', 1, 70, 32, 33),
(73, 'PPC中高阶实战特训营', 1, 70, 34, 35),
(74, '爆款打造精品课', 1, 70, 36, 37),
(75, '智慧卖家', 1, NULL, 49, 50),
(77, '非业绩库产品', 1, NULL, 51, 52),
(78, '大客户经理链接', 1, NULL, 53, 54),
(79, 'shopify独立站实战营', 1, 70, 38, 39),
(81, '亚马逊操盘手特训营', 1, 70, 40, 41),
(82, '企业内训', 1, 70, 42, 43),
(83, '亚马逊新人课', 1, 70, 44, 45),
(84, '跨境电商《组织核能-降本增效》', 1, 70, 46, 47),
(86, '次年续费', 1, 47, 12, 13),
(88, '店铺代注册', 1, NULL, 3, 4),
(89, '代持人资料', 1, 47, 10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `sys_configs`
--

CREATE TABLE `sys_configs` (
  `id` int(11) NOT NULL,
  `alias` char(20) NOT NULL COMMENT '配置代号',
  `title` varchar(20) DEFAULT NULL COMMENT '配置名',
  `value` varchar(200) DEFAULT NULL COMMENT '使用的值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统配置表';

--
-- Dumping data for table `sys_configs`
--

INSERT INTO `sys_configs` (`id`, `alias`, `title`, `value`) VALUES
(1, 'auth_visitor', '游客组权限', '1'),
(2, 'auth_regisiter', '注册组权限', '3'),
(3, 'auth_seller', '供应商组权限', '1'),
(4, 'uni_app_id', '推送uni_app_id', 'fRssHmn4KA6cVMvUFypGa4'),
(5, 'uni_app_key', '推送uni_app_key', 'OgbfP02M8xAzQmLQ66dst2'),
(6, 'uni_push_logo', '推送uni_push_logo', 'push.png'),
(7, 'uni_host', '推送uni_host', 'http://api.getui.com/apiex.htm'),
(8, 'uni_master_secret', '推送uni_master_secret', 'oR7kjLzY22AJBf4eAZpbv8'),
(9, 'brand_group_id', 'brand_group_id', '2'),
(10, 'store_group_id', 'store_group_id', '3'),
(11, 'seller_group_id', 'seller_group_id', '1');

-- --------------------------------------------------------

--
-- Table structure for table `sys_menus`
--

CREATE TABLE `sys_menus` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级菜单(若本身为顶级菜单，用0代替)',
  `title` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '菜单名',
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单路径',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `role` smallint(5) UNSIGNED DEFAULT NULL COMMENT '菜单权限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';

--
-- Dumping data for table `sys_menus`
--

INSERT INTO `sys_menus` (`id`, `parent_id`, `title`, `path`, `lft`, `rght`, `role`) VALUES
(1, NULL, '统计', NULL, 1, 8, 1),
(2, 1, '全部', '/statistic/all', 2, 3, 98),
(4, 1, '部门', '/statistic/department', 4, 5, 100),
(5, 1, '我的', '/statistic/mine', 6, 7, 102),
(6, NULL, '管理员', NULL, 55, 60, 1),
(7, 6, '新增管理员', '/admin/add', 56, 57, 3),
(8, 6, '管理员列表', '/admin/index', 58, 59, 24),
(9, NULL, '权限中心', NULL, 61, 66, 30),
(10, 9, '管理组', '/adminAro/index', 62, 63, 23),
(11, 9, '权限设置', '/adminAco/index', 64, 65, 31),
(12, NULL, '系统设置', NULL, 67, 72, 10),
(13, 12, '登录控制', '/hardConfig/loginControl', 68, 69, 21),
(14, 12, '菜单设置', '/sysMenu/index', 70, 71, 104),
(15, NULL, '客户管理', NULL, 9, 22, 48),
(16, NULL, '订单管理', NULL, 23, 34, 73),
(17, NULL, '产品管理', NULL, 35, 42, 84),
(18, NULL, '部门人员', NULL, 49, 54, 67),
(19, NULL, '合同管理', NULL, 43, 48, 36),
(20, 15, '客户独占查询', '/customer/finder', 10, 11, 66),
(21, 15, '新增客户', '/customer/add', 12, 13, 49),
(22, 15, '我的客户', '/customer/myCustomers', 14, 15, 50),
(23, 15, '部门客户', '/customer/departmentCustomers', 16, 17, 51),
(24, 15, '价值客户', '/customer/customers', 18, 19, 52),
(25, 15, '客户日志', '/customer/logs', 20, 21, 65),
(26, 16, '新增订单', '/order/add', 24, 25, 74),
(27, 16, '我的订单', '/order/myOrders', 26, 27, 77),
(28, 16, '部门订单', '/order/departmentOrders', 28, 29, 79),
(29, 16, '所有订单', '/order/orders', 30, 31, 78),
(30, 16, '订单日志', '/order/logs', 32, 33, 82),
(31, 17, '分类设置', '/product/category', 36, 37, 85),
(32, 17, '产品列表', '/product/index', 38, 39, 91),
(33, 19, '分类设置', '/contract/category', 44, 45, 37),
(34, 19, '合同列表', '/contract/index', 46, 47, 43),
(35, 18, '人员管理', '/department/index', 50, 51, 68),
(36, 18, '我的下属', '/department/myStaffs', 52, 53, 72),
(37, 17, '产品库', '/product/list', 40, 41, 111);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `AK_unique_username` (`username`) USING BTREE;

--
-- Indexes for table `admin_acos`
--
ALTER TABLE `admin_acos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_aros`
--
ALTER TABLE `admin_aros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_groups`
--
ALTER TABLE `admin_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_categories`
--
ALTER TABLE `contract_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_act_logs`
--
ALTER TABLE `customer_act_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_pools`
--
ALTER TABLE `customer_pools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_pool_recycles`
--
ALTER TABLE `customer_pool_recycles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_sale_logs`
--
ALTER TABLE `customer_sale_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hard_configs`
--
ALTER TABLE `hard_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_act_logs`
--
ALTER TABLE `order_act_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_recycles`
--
ALTER TABLE `order_recycles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_configs`
--
ALTER TABLE `sys_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_menus`
--
ALTER TABLE `sys_menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `admin_acos`
--
ALTER TABLE `admin_acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `admin_aros`
--
ALTER TABLE `admin_aros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_groups`
--
ALTER TABLE `admin_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_categories`
--
ALTER TABLE `contract_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_act_logs`
--
ALTER TABLE `customer_act_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_pools`
--
ALTER TABLE `customer_pools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_sale_logs`
--
ALTER TABLE `customer_sale_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `hard_configs`
--
ALTER TABLE `hard_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_act_logs`
--
ALTER TABLE `order_act_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `sys_configs`
--
ALTER TABLE `sys_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sys_menus`
--
ALTER TABLE `sys_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
