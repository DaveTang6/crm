-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-08-29 17:38:58
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `kkcrm`
--

-- --------------------------------------------------------

--
-- 表的结构 `admins`
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
-- 转存表中的数据 `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `mobile`, `truename`, `salt`, `reg_time`, `last_login`, `last_ip`, `group_id`, `visit_count`, `locked`, `enabled`) VALUES
(3, 'davetang', 'e9a58881a58c8908df350d8bea1119a3', 'dave1@intang.cn', '13108970971', '唐忠超', '215578', 1645165944, NULL, NULL, '[3]', 0, 0, 1),
(4, 'admin', '3615e8f0bc3861f6f9cacb57640542b2', '', '13108970911', '唐的雾', '624922', 1646968273, 1656984832, '127.0.0.1', '[1]', 1, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_acos`
--

CREATE TABLE `admin_acos` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级菜单(若本身为顶级菜单，用0代替)',
  `title` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `alias` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '别名',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `memo` text CHARACTER SET utf8,
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，0-不可用，1-可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单表';

--
-- 转存表中的数据 `admin_acos`
--

INSERT INTO `admin_acos` (`id`, `parent_id`, `title`, `alias`, `lft`, `rght`, `memo`, `status`) VALUES
(1, NULL, '管理员', NULL, 1, 18, '[]', 1),
(3, 1, '新增管理员', 'adminAdd', 2, 3, '[{\"r\":\"admins\\/add\",\"p\":0}]', 1),
(10, NULL, '系统设置', NULL, 19, 24, '[]', 1),
(15, 1, '删除管理员', NULL, 4, 5, '[{\"r\":\"admins\\/delete\",\"p\":0}]', 1),
(16, 1, '管理员修改', NULL, 6, 7, '[{\"r\":\"admins\\/update\",\"p\":0},{\"r\":\"admins\\/view\",\"p\":0}]', 1),
(17, 1, '个人设置', NULL, 8, 9, '[{\"r\":\"admins\\/selfupdate\",\"p\":0},{\"r\":\"admins\\/selfview\",\"p\":0}]', 1),
(18, 1, '登录', NULL, 10, 11, '[{\"r\":\"admins\\/login\",\"p\":0}]', 1),
(19, 1, '设置管理员状态', NULL, 12, 13, '[{\"r\":\"admins\\/setenabled\",\"p\":0}]', 1),
(20, 1, '状态解锁', NULL, 14, 15, '[{\"r\":\"admins\\/setunlocked\",\"p\":0}]', 1),
(21, 10, '登录控制', NULL, 20, 21, '[{\"r\":\"hardconfigs\\/update\\/admin_control\",\"p\":0},{\"r\":\"hardconfigs\\/view\\/admin_control\",\"p\":0}]', 1),
(22, NULL, '管理组', NULL, 25, 36, '[]', 1),
(23, 22, '管理组列表', NULL, 26, 27, '[{\"r\":\"adminaros\\/getlist\",\"p\":0}]', 1),
(24, 1, '管理员列表', NULL, 16, 17, '[{\"r\":\"admins\\/index\",\"p\":0}]', 1),
(25, 22, '新增管理组', NULL, 28, 29, '[{\"r\":\"adminaros\\/add\",\"p\":0}]', 1),
(26, 22, '管理组修改', NULL, 30, 31, '[{\"r\":\"adminaros\\/update\",\"p\":0},{\"r\":\"adminaros\\/view\",\"p\":0}]', 1),
(27, 22, '管理组复制', NULL, 32, 33, '[{\"r\":\"adminaros\\/copyaro\",\"p\":0}]', 1),
(28, 22, '删除管理组', NULL, 34, 35, '[{\"r\":\"adminaros\\/delete\",\"p\":0}]', 1),
(29, 30, '获取个人权限', NULL, 38, 39, '[{\"r\":\"adminaros\\/getselfaros\",\"p\":0}]', 1),
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
(42, 36, '新增合同', '', 62, 63, '[{\"r\":\"contracts\\/add\",\"p\":0}]', 1),
(43, 36, '合同列表', '', 64, 65, '[{\"r\":\"contracts\\/index\",\"p\":0}]', 1),
(44, 36, '设置合同状态', '', 66, 67, '[{\"r\":\"contracts\\/setstatus\",\"p\":0}]', 1),
(45, 36, '删除合同', '', 68, 69, '[{\"r\":\"contracts\\/delete\",\"p\":0}]', 1),
(46, 36, '修改合同', '', 70, 71, '[{\"r\":\"contracts\\/update\",\"p\":0},{\"r\":\"contracts\\/view\",\"p\":0}]', 1),
(47, 36, '查看合同', '', 72, 73, '[{\"r\":\"contracts\\/view\",\"p\":0}]', 1),
(48, NULL, '客户管理', '', 75, 114, '[]', 1),
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
(67, NULL, '部门人员', '', 115, 126, '[]', 1),
(68, 67, '获取全部人员', '', 116, 117, '[{\"r\":\"departments\\/index\",\"p\":0}]', 1),
(69, 67, '获取部门人员', '', 118, 119, '[{\"r\":\"departments\\/getstaffs\",\"p\":0}]', 1),
(70, 67, '删除部门人员', '', 120, 121, '[{\"r\":\"departments\\/delete\",\"p\":0}]', 1),
(71, 67, '新增部门员工', '', 122, 123, '[{\"r\":\"departments\\/addstaff\",\"p\":0}]', 1),
(72, 67, '我的下属', '', 124, 125, '[{\"r\":\"departments\\/mystaffs\",\"p\":0}]', 1),
(73, NULL, '订单管理', '', 127, 148, '[]', 1),
(74, 73, '新增订单', '', 128, 129, '[{\"r\":\"orders\\/add\",\"p\":0}]', 1),
(75, 73, '订单修改', '', 130, 131, '[{\"r\":\"orders\\/update\",\"p\":0}]', 1),
(76, 73, '删除订单', '', 132, 133, '[{\"r\":\"orders\\/delete\",\"p\":0}]', 1),
(77, 73, '我的订单', '', 134, 135, '[{\"r\":\"orders\\/myorders\",\"p\":0}]', 1),
(78, 73, '全部订单', '', 136, 137, '[{\"r\":\"orders\\/getorders\",\"p\":0}]', 1),
(79, 73, '部门订单', '', 138, 139, '[{\"r\":\"orders\\/departmentorders\",\"p\":0}]', 1),
(80, 73, '查看订单', '', 140, 141, '[{\"r\":\"orders\\/view\",\"p\":0}]', 1),
(81, 73, '订单查看+', '', 142, 143, '[{\"r\":\"orders\\/viewplus\",\"p\":0}]', 1),
(82, 73, '订单日志列表', '', 144, 145, '[{\"r\":\"orderactlogs\\/actlogs\",\"p\":0}]', 1),
(83, 73, '订单财务确认', '', 146, 147, '[{\"r\":\"orders\\/updatefinance\",\"p\":0}]', 1),
(84, NULL, '产品管理', '', 149, 174, '[]', 1),
(85, 84, '新增产品分类', '', 150, 151, '[{\"r\":\"productcategories\\/add\",\"p\":0}]', 1),
(86, 84, '修改产品分类', '', 152, 153, '[{\"r\":\"productcategories\\/update\",\"p\":0},{\"r\":\"productcategories\\/view\",\"p\":0}]', 1),
(87, 84, '获取产品分类', '', 154, 155, '[{\"r\":\"productcategories\\/getlist\",\"p\":0},{\"r\":\"productcategories\\/getkeypath\",\"p\":0}]', 1),
(88, 84, '移动产品分类', '', 156, 157, '[{\"r\":\"productcategories\\/move\",\"p\":0}]', 1),
(89, 84, '删除产品分类', '', 158, 159, '[{\"r\":\"productcategories\\/delete\",\"p\":0}]', 1),
(90, 84, '查看产品分类', '', 160, 161, '[{\"r\":\"productcategories\\/view\",\"p\":0}]', 1),
(91, 84, '新增产品', '', 162, 163, '[{\"r\":\"products\\/add\",\"p\":0}]', 1),
(92, 84, '获取产品', '', 164, 165, '[{\"r\":\"products\\/index\",\"p\":0}]', 1),
(93, 84, '设置产品状态', '', 166, 167, '[{\"r\":\"products\\/setstatus\",\"p\":0}]', 1),
(94, 84, '删除产品', '', 168, 169, '[{\"r\":\"products\\/delete\",\"p\":0}]', 1),
(95, 84, '修改产品信息', '', 170, 171, '[{\"r\":\"products\\/update\",\"p\":0},{\"r\":\"products\\/view\",\"p\":0}]', 1),
(96, 84, '查看产品信息', '', 172, 173, '[{\"r\":\"products\\/view\",\"p\":0}]', 1),
(97, NULL, '统计', '', 175, 188, '[]', 1),
(98, 97, '统计所有客户', '', 176, 177, '[{\"r\":\"customerpools\\/statisticall\",\"p\":0}]', 1),
(99, 97, '统计所有订单', '', 178, 179, '[{\"r\":\"orders\\/statisticall\",\"p\":0}]', 1),
(100, 97, '统计部门客户', '', 180, 181, '[{\"r\":\"customerpools\\/statisticdepartment\",\"p\":0}]', 1),
(101, 97, '统计部门订单', '', 182, 183, '[{\"r\":\"orders\\/statisticdepartment\'\",\"p\":0}]', 1),
(102, 97, '统计我的客户', '', 184, 185, '[{\"r\":\"customerpools\\/statisticmine\",\"p\":0}]', 1),
(103, 97, '统计我的订单', '', 186, 187, '[{\"r\":\"orders\\/statisticmine\",\"p\":0}]', 1),
(104, 10, '菜单设置', '', 22, 23, '[{\"r\":\"sysmenus\\/add\",\"p\":0},{\"r\":\"sysmenus\\/update\",\"p\":0},{\"r\":\"sysmenus\\/getlist\",\"p\":0},{\"r\":\"sysmenus\\/getmenu\",\"p\":0},{\"r\":\"sysmenus\\/getkeypath\",\"p\":0},{\"r\":\"sysmenus\\/move\",\"p\":0},{\"r\":\"sysmenus\\/delete\",\"p\":0},{\"r\":\"sysmenus\\/view\",\"p\":0}]', 1),
(105, 48, '释放已过期客户', '', 112, 113, '[{\"r\":\"customerpools\\/gettimeoutcustomers\",\"p\":0}]', 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_aros`
--

CREATE TABLE `admin_aros` (
  `id` int(11) NOT NULL,
  `alias` varchar(20) NOT NULL COMMENT '用户组名',
  `allowed` text COMMENT '可访问的资源',
  `denied` text COMMENT '禁止访问的资源'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户组权限';

--
-- 转存表中的数据 `admin_aros`
--

INSERT INTO `admin_aros` (`id`, `alias`, `allowed`, `denied`) VALUES
(1, '超级管理员', '[3,15,16,17,18,19,20,24,1,21,104,10,23,25,26,27,28,22,29,31,32,33,34,35,30,37,38,39,40,41,42,43,44,45,46,47,36,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,48,68,69,70,71,72,67,74,75,76,77,78,79,80,81,82,83,73,85,86,87,88,89,90,91,92,93,94,95,96,84,98,99,100,101,102,103,97]', '[]'),
(4, '游客', '[18,1,105,48]', NULL),
(5, '销售经理', NULL, NULL),
(6, '销售主管', NULL, NULL),
(7, '销售', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `admin_groups`
--

CREATE TABLE `admin_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `admin_aro_id` int(10) UNSIGNED NOT NULL COMMENT '用户组id',
  `begin_time` int(11) DEFAULT NULL COMMENT '会员开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '会员结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户组';

--
-- 转存表中的数据 `admin_groups`
--

INSERT INTO `admin_groups` (`id`, `admin_id`, `admin_aro_id`, `begin_time`, `end_time`) VALUES
(20, 4, 1, NULL, NULL),
(21, 3, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL COMMENT '合同名',
  `content` varchar(200) DEFAULT NULL COMMENT '合同介绍',
  `file_url` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，0-不可用，1-可用',
  `category_id` int(11) NOT NULL,
  `add_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='合同';

--
-- 转存表中的数据 `contracts`
--

INSERT INTO `contracts` (`id`, `title`, `content`, `file_url`, `status`, `category_id`, `add_time`) VALUES
(1, '侃侃卡片合同2', '200-300w卡片合同', '22\\08\\03\\22080306031589718.doc', 1, 37, 0);

-- --------------------------------------------------------

--
-- 表的结构 `contract_categories`
--

CREATE TABLE `contract_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-不可用，1-可用',
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `contract_categories`
--

INSERT INTO `contract_categories` (`id`, `title`, `status`, `parent_id`, `lft`, `rght`) VALUES
(33, '卡片邀评', 1, NULL, 5, 10),
(39, '侃侃会员', 1, 36, 2, 3),
(36, '会员类', 1, NULL, 1, 4),
(37, '卡片', 1, 33, 6, 7),
(43, '地址导出', 1, 33, 8, 9);

-- --------------------------------------------------------

--
-- 表的结构 `customers`
--

CREATE TABLE `customers` (
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
  `level` varchar(5) DEFAULT NULL COMMENT '意向登记',
  `is_bl_member` tinyint(4) DEFAULT NULL COMMENT '是否白鹿会0-否，1-是',
  `saler_id` int(11) DEFAULT NULL COMMENT '所属销售',
  `locked_by_user` varchar(30) DEFAULT NULL COMMENT '锁定人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_buy` int(11) DEFAULT NULL COMMENT '最后购买时间',
  `order_count` int(11) DEFAULT '0' COMMENT '下单数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

--
-- 转存表中的数据 `customers`
--

INSERT INTO `customers` (`id`, `wechat`, `truename`, `mobile`, `gender`, `area`, `company`, `department`, `brand`, `memo`, `level`, `is_bl_member`, `saler_id`, `locked_by_user`, `add_time`, `last_buy`, `order_count`) VALUES
(6, 'real_moss', 'MOSS2', '13108970212', 1, '深圳', '深圳侃侃', '老板', '侃侃', 'BOSS', 'A', 1, 4, '唐的雾', 1659752288, 1660867200, 3),
(7, 'bubuy', '唐忠超', '', 2, NULL, NULL, NULL, NULL, NULL, 'C', 0, 3, NULL, 1659752288, NULL, 0),
(10, 'bbq', '巴比奇', '13108970999', 0, '重庆', '看看', '客户不', '看看', NULL, 'C', 0, 4, NULL, 1660879379, 1660867200, 1);

-- --------------------------------------------------------

--
-- 表的结构 `customer_act_logs`
--

CREATE TABLE `customer_act_logs` (
  `id` int(11) NOT NULL,
  `customer_pool_id` int(11) NOT NULL COMMENT '客户池中的id',
  `customer_wechat` varchar(30) NOT NULL COMMENT '客户微信号',
  `act` varchar(10) NOT NULL COMMENT '操作行为',
  `act_user` varchar(20) DEFAULT NULL COMMENT '操作人',
  `act_user_id` int(11) NOT NULL COMMENT '操作人id',
  `add_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='客户池客户操作日志';

--
-- 转存表中的数据 `customer_act_logs`
--

INSERT INTO `customer_act_logs` (`id`, `customer_pool_id`, `customer_wechat`, `act`, `act_user`, `act_user_id`, `add_time`) VALUES
(32, 0, 'bbq', 'create', '唐的雾', 4, 1660879403),
(31, 26, 'dave', 'delete', '唐的雾', 4, 1660792382),
(30, 0, 'dave', 'create', '唐的雾', 4, 1660788998),
(29, 25, 'real_moss', 'assign', '唐的雾', 4, 1660721171),
(28, 25, 'real_moss', 'release', '唐的雾', 4, 1660720142),
(27, 25, 'real_moss', 'update', '唐的雾', 4, 1660277024),
(26, 25, 'real_moss', 'update', '唐的雾', 4, 1660271555),
(25, 25, 'real_moss', 'update', '唐的雾', 4, 1660270894),
(24, 25, 'real_moss', 'update', '唐的雾', 4, 1660270782),
(23, 25, 'real_moss', 'update', '唐的雾', 4, 1660270217),
(22, 25, 'real_moss', 'update', '唐的雾', 4, 1660270050),
(21, 25, 'real_moss', 'update', '唐的雾', 4, 1660197949),
(20, 25, 'real_moss', 'update', '唐的雾', 4, 1660190367),
(19, 0, 'real_moss', 'create', '唐的雾', 4, 1660190319),
(33, 25, 'real_moss', 'release', '唐的雾', 4, 1660879454),
(34, 27, 'bbq', 'release', 'System', 0, 1660881552),
(35, 25, 'real_moss', 'update', '唐的雾', 4, 1660881601);

-- --------------------------------------------------------

--
-- 表的结构 `customer_pools`
--

CREATE TABLE `customer_pools` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

--
-- 转存表中的数据 `customer_pools`
--

INSERT INTO `customer_pools` (`id`, `wechat`, `truename`, `mobile`, `gender`, `area`, `company`, `department`, `brand`, `memo`, `is_bl_member`, `locked_by_user_id`, `locked_by_user`, `locked_time`, `add_time`, `last_buy`, `order_count`) VALUES
(25, 'real_moss', 'MOSS2', '13108970212', 1, '深圳', '深圳侃侃', '老板', '侃侃', 'asdasd123', 1, 4, '唐的雾', 1660881601, 1660190797, 1660867200, 3),
(27, 'bbq', '巴比奇', '13108970999', 0, '重庆', '看看', '客户不', '看看', NULL, 0, 0, NULL, NULL, 1660879403, 1660867200, 1);

-- --------------------------------------------------------

--
-- 表的结构 `customer_pool_recycles`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='我的客户';

--
-- 转存表中的数据 `customer_pool_recycles`
--

INSERT INTO `customer_pool_recycles` (`id`, `wechat`, `truename`, `mobile`, `gender`, `area`, `company`, `department`, `brand`, `memo`, `is_bl_member`, `locked_by_user_id`, `locked_by_user`, `locked_time`, `add_time`, `last_buy`, `order_count`) VALUES
(26, 'dave', 'tang', '18623333986', 2, '重庆', '知谓', '技术', '侃侃', NULL, 0, 4, '唐的雾', 1660788998, 1660788998, 1660780800, 1);

-- --------------------------------------------------------

--
-- 表的结构 `customer_sale_logs`
--

CREATE TABLE `customer_sale_logs` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT '客户id',
  `saler_id` int(11) NOT NULL COMMENT '销售id',
  `content` varchar(255) NOT NULL COMMENT '日志',
  `timestamp` int(11) NOT NULL COMMENT '日志录入时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='客户销售日志';

--
-- 转存表中的数据 `customer_sale_logs`
--

INSERT INTO `customer_sale_logs` (`id`, `customer_id`, `saler_id`, `content`, `timestamp`) VALUES
(4, 7, 4, '来啊来啊', 1659744000),
(3, 7, 4, '懂懂懂', 1659657600);

-- --------------------------------------------------------

--
-- 表的结构 `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL COMMENT '经理的user_id',
  `staff_id` int(11) NOT NULL COMMENT '员工user_id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='部门成员表';

--
-- 转存表中的数据 `departments`
--

INSERT INTO `departments` (`id`, `manager_id`, `staff_id`) VALUES
(9, 4, 4),
(10, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `hard_configs`
--

CREATE TABLE `hard_configs` (
  `id` int(11) NOT NULL,
  `alias` char(20) NOT NULL,
  `value` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='系统必要配置';

--
-- 转存表中的数据 `hard_configs`
--

INSERT INTO `hard_configs` (`id`, `alias`, `value`) VALUES
(1, 'admin_control', '{\"guestGroup\":4,\"blockTime\":1646064000,\"loginDeny\":0,\"userNum\":100,\"loginErrorNum\":5,\"loginLockTime\":60,\"usingTime\":50,\"lockedDays\":1}');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
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
  `pay_proof_url` varchar(255) DEFAULT NULL COMMENT '支付凭证地址',
  `memo` varchar(255) DEFAULT NULL COMMENT '订单说明',
  `saler_id` int(11) NOT NULL COMMENT '销售id',
  `refund_status` tinyint(4) DEFAULT NULL COMMENT '是否有退款，1-有，其他无',
  `refund` int(11) DEFAULT '0' COMMENT '退款金额',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付状态，0-未确认，1-已确认',
  `add_time` int(11) NOT NULL COMMENT '订单添加时间',
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `product_category` varchar(30) DEFAULT NULL COMMENT '商品分类名',
  `product_name` varchar(30) DEFAULT NULL COMMENT '销售产品名',
  `customer_name` varchar(20) DEFAULT NULL COMMENT '客户姓名',
  `saler_name` varchar(20) DEFAULT NULL COMMENT '销售姓名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='订单';

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `customer_wechat`, `customer_pool_id`, `product_id`, `product_category_id`, `price`, `cost`, `num`, `order_time`, `pay_time`, `contract_url`, `pay_proof_url`, `memo`, `saler_id`, `refund_status`, `refund`, `pay_status`, `add_time`, `order_no`, `product_category`, `product_name`, `customer_name`, `saler_name`) VALUES
(32, 'real_moss', 25, 2, 33, 6666, 1980, 66, 1659744000, 1660608000, '22\\08\\15\\22081503214763609.doc', '22\\08\\15\\22081503215153116.png', '123123', 4, 1, 0, 1, 1660277024, '2022081213383', '卡片', '美国卡片1', 'MOSS2', '唐的雾'),
(25, 'real_moss', 25, 2, 33, 888, 300, 10, 1660089600, 1660608000, NULL, NULL, NULL, 4, 1, 0, 1, 1660190367, '2022081137231', NULL, '美国卡片1', 'MOSS1', '唐的雾'),
(33, 'dave', 26, 2, 33, 1000, 30, 1, 1660780800, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660788998, '202208181656452', '卡片', '美国卡片1', 'tang', '唐的雾'),
(34, 'bbq', 27, 3, 39, 1000, 900, 1, 1660867200, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660879403, '202208192395963', '侃侃大学', '侃侃大学学习', '巴比奇', '唐的雾'),
(35, 'real_moss', 25, 2, 33, 999, 270, 9, 1660867200, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660881601, '202208190068882', '卡片', '美国卡片1', 'MOSS2', '唐的雾');

-- --------------------------------------------------------

--
-- 表的结构 `order_act_logs`
--

CREATE TABLE `order_act_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT '订单中的id',
  `order_no` varchar(30) NOT NULL COMMENT '订单号',
  `act` varchar(10) NOT NULL COMMENT '操作行为',
  `act_user` varchar(20) NOT NULL COMMENT '操作人',
  `act_user_id` int(11) NOT NULL COMMENT '操作人id',
  `add_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='客户池客户操作日志';

--
-- 转存表中的数据 `order_act_logs`
--

INSERT INTO `order_act_logs` (`id`, `order_id`, `order_no`, `act`, `act_user`, `act_user_id`, `add_time`) VALUES
(26, 32, '2022081213383', 'update', '唐的雾', 4, 1660534226),
(25, 32, '2022081213383', 'update', '唐的雾', 4, 1660534213),
(24, 32, '2022081213383', 'update', '唐的雾', 4, 1660534014),
(23, 30, '2022081280944', 'delete', '唐的雾', 4, 1660277053),
(22, 32, '2022081213383', 'create', '唐的雾', 4, 1660277024),
(21, 31, '2022081279224', 'create', '唐的雾', 4, 1660271555),
(20, 30, '2022081280944', 'create', '唐的雾', 4, 1660270894),
(19, 29, '2022081252589', 'create', '唐的雾', 4, 1660270782),
(18, 28, '2022081206385', 'create', '唐的雾', 4, 1660270217),
(17, 27, '2022081278140', 'create', '唐的雾', 4, 1660270050),
(16, 26, '2022081155829', 'create', '唐的雾', 4, 1660197949),
(15, 25, '2022081137231', 'create', '唐的雾', 4, 1660190367),
(14, 24, '2022081166730', 'create', '唐的雾', 4, 1660190319),
(27, 32, '2022081213383', 'update', '唐的雾', 4, 1660534287),
(28, 32, '2022081213383', 'update', '唐的雾', 4, 1660534365),
(29, 32, '2022081213383', 'update', '唐的雾', 4, 1660534745),
(30, 32, '2022081213383', 'update', '唐的雾', 4, 1660534770),
(31, 32, '2022081213383', 'update', '唐的雾', 4, 1660537538),
(32, 32, '2022081213383', 'update', '唐的雾', 4, 1660537776),
(33, 32, '2022081213383', 'update', '唐的雾', 4, 1660537862),
(34, 32, '2022081213383', 'update', '唐的雾', 4, 1660537912),
(35, 32, '2022081213383', 'update', '唐的雾', 4, 1660544620),
(36, 25, '2022081137231', 'pay', '唐的雾', 4, 1660623310),
(37, 25, '2022081137231', 'refund', '唐的雾', 4, 1660623310),
(38, 33, '202208181656452', 'create', '唐的雾', 4, 1660788998),
(39, 34, '202208192395963', 'create', '唐的雾', 4, 1660879403),
(40, 35, '202208190068882', 'create', '唐的雾', 4, 1660881601);

-- --------------------------------------------------------

--
-- 表的结构 `order_recycles`
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
  `saler_name` varchar(20) DEFAULT NULL COMMENT '销售姓名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='订单';

--
-- 转存表中的数据 `order_recycles`
--

INSERT INTO `order_recycles` (`id`, `customer_wechat`, `customer_pool_id`, `product_id`, `product_category_id`, `price`, `cost`, `num`, `order_time`, `pay_time`, `contract_url`, `pay_proof_url`, `memo`, `saler_id`, `refund_status`, `refund`, `pay_status`, `add_time`, `order_no`, `product_category`, `product_name`, `customer_name`, `saler_name`) VALUES
(31, 'real_moss', 25, 2, 33, 1999, 3000, 100, 1660262400, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660271555, '2022081279224', NULL, '美国卡片1', 'MOSS2', '唐的雾'),
(30, 'real_moss', 25, 2, 33, 1000, 300, 10, 1660262400, NULL, NULL, NULL, NULL, 4, NULL, 0, 0, 1660270894, '2022081280944', NULL, '美国卡片1', 'MOSS2', '唐的雾');

-- --------------------------------------------------------

--
-- 表的结构 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL COMMENT '商品名称',
  `price` int(11) DEFAULT '0' COMMENT '指导价格',
  `cost` int(11) DEFAULT '0' COMMENT '成本',
  `category_id` int(11) DEFAULT NULL COMMENT '分类id',
  `status` tinyint(4) DEFAULT '1' COMMENT '0-不可用，1-可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='商品';

--
-- 转存表中的数据 `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `cost`, `category_id`, `status`) VALUES
(3, '侃侃大学学习', 999, 900, 39, 1),
(2, '美国卡片1', 50, 30, 43, 1);

-- --------------------------------------------------------

--
-- 表的结构 `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-不可用，1-可用',
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `product_categories`
--

INSERT INTO `product_categories` (`id`, `title`, `status`, `parent_id`, `lft`, `rght`) VALUES
(33, '卡片', 1, NULL, 5, 10),
(39, '侃侃大学', 1, 36, 2, 3),
(36, '在线教学', 1, NULL, 1, 4),
(37, '卡片邀评', 1, 33, 6, 7),
(43, '导出地址', 1, 33, 8, 9),
(44, '线下课', 1, NULL, 11, 16),
(45, 'Gary选品', 1, 44, 12, 13),
(46, 'MOSS小班课', 1, 44, 14, 15);

-- --------------------------------------------------------

--
-- 表的结构 `sys_configs`
--

CREATE TABLE `sys_configs` (
  `id` int(11) NOT NULL,
  `alias` char(20) NOT NULL COMMENT '配置代号',
  `title` varchar(20) DEFAULT NULL COMMENT '配置名',
  `value` varchar(200) DEFAULT NULL COMMENT '使用的值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='系统配置表';

--
-- 转存表中的数据 `sys_configs`
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
-- 表的结构 `sys_menus`
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
-- 转存表中的数据 `sys_menus`
--

INSERT INTO `sys_menus` (`id`, `parent_id`, `title`, `path`, `lft`, `rght`, `role`) VALUES
(1, NULL, '统计', NULL, 1, 8, 1),
(2, 1, '全部', '/statistic/all', 2, 3, 98),
(4, 1, '部门', '/statistic/department', 4, 5, 100),
(5, 1, '我的', '/statistic/mine', 6, 7, 102),
(6, NULL, '管理员', NULL, 53, 58, 1),
(7, 6, '新增管理员', '/admin/add', 54, 55, 3),
(8, 6, '管理员列表', '/admin/index', 56, 57, 24),
(9, NULL, '权限中心', NULL, 59, 64, 30),
(10, 9, '管理组', '/adminAro/index', 60, 61, 23),
(11, 9, '权限设置', '/adminAco/index', 62, 63, 31),
(12, NULL, '系统设置', NULL, 65, 70, 10),
(13, 12, '登录控制', '/hardConfig/loginControl', 66, 67, 21),
(14, 12, '菜单设置', '/sysMenu/index', 68, 69, 104),
(15, NULL, '客户管理', NULL, 9, 22, 48),
(16, NULL, '订单管理', NULL, 23, 34, 73),
(17, NULL, '产品管理', NULL, 35, 40, 84),
(18, NULL, '部门人员', NULL, 47, 52, 67),
(19, NULL, '合同管理', NULL, 41, 46, 36),
(20, 15, '客户独占查询', '/customer/finder', 10, 11, 66),
(21, 15, '新增客户', '/customer/add', 12, 13, 49),
(22, 15, '我的客户', '/customer/myCustomers', 14, 15, 50),
(23, 15, '部门客户', '/customer/departmentCustomers', 16, 17, 51),
(24, 15, '客户池', '/customer/customers', 18, 19, 52),
(25, 15, '客户日志', '/customer/logs', 20, 21, 65),
(26, 16, '新增订单', '/order/add', 24, 25, 74),
(27, 16, '我的订单', '/order/myOrders', 26, 27, 77),
(28, 16, '部门订单', '/order/departmentOrders', 28, 29, 79),
(29, 16, '所有订单', '/order/orders', 30, 31, 78),
(30, 16, '订单日志', '/order/logs', 32, 33, 82),
(31, 17, '分类设置', '/product/category', 36, 37, 87),
(32, 17, '产品列表', '/product/index', 38, 39, 92),
(33, 19, '分类设置', '/contract/category', 42, 43, 39),
(34, 19, '合同列表', '/contract/index', 44, 45, 43),
(35, 18, '人员管理', '/department/index', 48, 49, 68),
(36, 18, '我的下属', '/department/myStaffs', 50, 51, 72);

--
-- 转储表的索引
--

--
-- 表的索引 `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `AK_unique_username` (`username`) USING BTREE;

--
-- 表的索引 `admin_acos`
--
ALTER TABLE `admin_acos`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_aros`
--
ALTER TABLE `admin_aros`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_groups`
--
ALTER TABLE `admin_groups`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `contract_categories`
--
ALTER TABLE `contract_categories`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `customer_act_logs`
--
ALTER TABLE `customer_act_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `customer_pools`
--
ALTER TABLE `customer_pools`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `customer_pool_recycles`
--
ALTER TABLE `customer_pool_recycles`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `customer_sale_logs`
--
ALTER TABLE `customer_sale_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hard_configs`
--
ALTER TABLE `hard_configs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `order_act_logs`
--
ALTER TABLE `order_act_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `order_recycles`
--
ALTER TABLE `order_recycles`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `sys_configs`
--
ALTER TABLE `sys_configs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `sys_menus`
--
ALTER TABLE `sys_menus`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `admin_acos`
--
ALTER TABLE `admin_acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- 使用表AUTO_INCREMENT `admin_aros`
--
ALTER TABLE `admin_aros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `admin_groups`
--
ALTER TABLE `admin_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `contract_categories`
--
ALTER TABLE `contract_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- 使用表AUTO_INCREMENT `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `customer_act_logs`
--
ALTER TABLE `customer_act_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- 使用表AUTO_INCREMENT `customer_pools`
--
ALTER TABLE `customer_pools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- 使用表AUTO_INCREMENT `customer_sale_logs`
--
ALTER TABLE `customer_sale_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `hard_configs`
--
ALTER TABLE `hard_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- 使用表AUTO_INCREMENT `order_act_logs`
--
ALTER TABLE `order_act_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 使用表AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- 使用表AUTO_INCREMENT `sys_configs`
--
ALTER TABLE `sys_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `sys_menus`
--
ALTER TABLE `sys_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
