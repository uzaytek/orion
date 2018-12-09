-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orion`
--

-- --------------------------------------------------------

--
-- Table structure for table `orion_admins`
--

CREATE TABLE `orion_admins` (
  `adminid` int(10) UNSIGNED NOT NULL,
  `adminname` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `adminemail` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `adminpasswd` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `adminsid` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_admins`
--

INSERT INTO `orion_admins` (`adminid`, `adminname`, `adminemail`, `adminpasswd`, `adminsid`) VALUES
(1, 'demo', 'test@', 'fe01ce2a7fbac8fafaed7c982a04e229', 'd99df46ae433b620c58b9098b14cd5a1');

-- --------------------------------------------------------

--
-- Table structure for table `orion_banners`
--

CREATE TABLE `orion_banners` (
  `fileid` int(9) UNSIGNED NOT NULL,
  `filename` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `origname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` enum('left','right','top') COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isonline` tinyint(4) DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_banners`
--

INSERT INTO `orion_banners` (`fileid`, `filename`, `origname`, `location`, `url`, `isonline`, `isdeleted`, `dtcreated`) VALUES
(1, '3aSptzaf47dvAEVWDXD8Bk29sS2nwx5n.jpg', 'Angel Skype.jpg', 'left', 'dfadsfdafa', NULL, 1, '2012-03-17 08:33:46'),
(2, 'yHrmUhwyzFhwrfZSStuFhvMdxBF3UZ3m.jpg', '0764549669.jpg', 'right', 'adsfadsafdadsf', NULL, 1, '2012-03-17 08:34:33'),
(3, 'nvEHp6k7fwaw5VknMBmYbKTcsaW66yFm.jpg', '1565926811.jpg', 'left', 'http://www.phpcookbook.com', NULL, 1, '2012-05-01 09:48:37'),
(4, 'ccUzyCVR9wP3KBnTd8Kk8m8AMAdxU3DR.gif', 'banner_orta_ust.gif', 'top', 'http://www.agaclar.net/forum/', NULL, 1, '2012-05-01 09:49:50'),
(5, 'aWPvdYwHd2w2kMSZz4s6tTuxamHeawkB.gif', 'dBa5PmycaFKp8Vszk4xHXFmKPU9B9k45.gif', 'right', 'http://www.phpcookbook.com', NULL, 1, '2012-05-03 17:35:39'),
(6, 'rVSZdEke8efxZVawwp5AESPu6ZSZbeUf.jpg', 'banner.jpg', 'right', 'basket.php?act=add&id=eGHJ0krvLcMY4m0niume4k8fPgf8n9Fh', NULL, 0, '2012-05-07 12:46:16'),
(7, 'BFaAwCa7B3356eThBRH8396URFbwE7xs.gif', '180x150.gif', 'left', 'http://www.uzaytek.com', NULL, 0, '2012-05-07 14:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `orion_baskets`
--

CREATE TABLE `orion_baskets` (
  `basketid` int(11) NOT NULL,
  `productid` int(11) NOT NULL DEFAULT '0',
  `productstatus` tinyint(4) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `paymentid` int(11) NOT NULL,
  `paymentkey` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `itemcount` smallint(6) DEFAULT '0',
  `dtcreated` datetime NOT NULL,
  `dtmodified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_baskets`
--

INSERT INTO `orion_baskets` (`basketid`, `productid`, `productstatus`, `userid`, `paymentid`, `paymentkey`, `price`, `itemcount`, `dtcreated`, `dtmodified`) VALUES
(1, 2, 0, 0, 0, '20120319134610_8860', '0.0000', 1, '2012-03-19 13:58:03', NULL),
(2, 2, 0, 0, 11, '20120319161956_24240', '0.0000', 1, '2012-03-19 16:20:00', NULL),
(3, 2, 0, 0, 0, '20120320091307_35135', '0.0000', 1, '2012-03-20 09:13:12', NULL),
(4, 2, 0, 0, 15, '20120320101335_87513', '0.0000', 1, '2012-03-20 10:18:13', NULL),
(5, 1, 0, 0, 0, '20120320171119_31103', '0.0000', 1, '2012-03-20 17:11:20', NULL),
(6, 2, 0, 0, 0, '20120326155323_11984', '0.0000', 1, '2012-03-26 15:53:23', NULL),
(7, 2, 0, 0, 0, '20120327105311_82632', '0.0000', 1, '2012-03-27 10:59:20', NULL),
(8, 2, 0, 0, 0, '20120328132026_94838', '0.0000', 1, '2012-03-28 13:20:26', NULL),
(9, 2, 0, 0, 0, '20120329095101_41487', '0.0000', 1, '2012-03-29 09:51:01', NULL),
(10, 2, 0, 0, 0, '20120329121519_58501', '0.0000', 1, '2012-03-29 12:15:25', NULL),
(11, 2, 0, 0, 0, '20120409184806_43694', '0.0000', 1, '2012-04-09 18:48:06', NULL),
(12, 2, 0, 0, 0, '20120412082809_96776', '0.0000', 1, '2012-04-12 08:28:54', NULL),
(13, 2, 0, 0, 0, '20120417124902_10519', '0.0000', 1, '2012-04-17 12:49:02', NULL),
(14, 34, 0, 0, 0, '20120417124902_10519', '0.0000', 1, '2012-04-17 13:26:34', NULL),
(15, 2, 0, 0, 0, '20120418151407_95289', '0.0000', 1, '2012-04-18 15:14:07', NULL),
(17, 8, 0, 0, 16, '20120418161044_5266', '0.0000', 1, '2012-04-18 16:13:26', NULL),
(18, 2, 0, 2, 16, '20120418161044_5266', '0.0000', 1, '2012-04-18 16:40:40', NULL),
(20, 2, 0, 2, 19, '20120419141559_28285', '32.3400', 3, '2012-04-19 15:07:23', NULL),
(21, 2, 0, 2, 24, '20120425132009_80873', '32.3400', 1, '2012-04-25 13:20:14', NULL),
(22, 7, 0, 2, 0, '20120430175915_56029', '324.0000', 1, '2012-04-30 17:59:15', NULL),
(23, 2, 0, 2, 0, '20120504170342_48350', '32.3400', 1, '2012-05-04 17:03:49', NULL),
(24, 1, 0, 2, 25, '20120505142243_45676', '99.5500', 2, '2012-05-05 14:22:43', NULL),
(25, 2, 0, 2, 25, '20120505142243_45676', '399.9500', 1, '2012-05-05 15:23:17', NULL),
(26, 1, 0, 2, 4, '20120507092622_77823', '99.5500', 2, '2012-05-07 09:26:22', NULL),
(29, 3, 0, 2, 0, '20120507125447_84227', '980.0000', 1, '2012-05-07 12:57:42', NULL),
(30, 1, 0, 2, 0, '20120507141410_58293', '99.5500', 1, '2012-05-07 14:14:10', NULL),
(31, 3, 0, 2, 0, '20120817152719_8154', '980.0000', 3, '2012-08-17 15:27:19', NULL),
(32, 1, 0, 2, 0, '20121005140858_4829', '99.5500', 2, '2012-10-05 14:08:58', NULL),
(33, 2, 0, 2, 0, '20121005140858_4829', '399.9500', 1, '2012-10-05 14:09:13', NULL),
(34, 3, 0, 2, 0, '20121224172406_99965', '980.0000', 1, '2012-12-24 17:24:06', NULL),
(35, 3, 0, 2, 0, '20130326135421_82546', '980.0000', 1, '2013-03-26 13:54:28', NULL),
(36, 1, 0, 2, 0, '20130905164627_81903', '99.5500', 1, '2013-09-05 16:46:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_categories`
--

CREATE TABLE `orion_categories` (
  `catid` int(11) NOT NULL,
  `parentcatid` int(11) NOT NULL DEFAULT '0',
  `cattitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `catorder` tinyint(4) NOT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_categories`
--

INSERT INTO `orion_categories` (`catid`, `parentcatid`, `cattitle`, `catorder`, `isdeleted`, `dtcreated`) VALUES
(3, 0, 'Sony', 2, NULL, '2012-03-14 13:40:34'),
(4, 0, 'HTC', 3, NULL, '2012-03-14 13:40:46'),
(5, 3, 'a third one', 0, 1, '2012-03-15 13:48:18'),
(6, 0, 'Apple', 1, NULL, '2012-03-16 09:20:11'),
(7, 3, 'a new one 4 11', 2, 1, '2012-03-16 09:20:32'),
(8, 6, 'Apple Iphone 3G', 0, NULL, '2012-03-16 09:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `orion_countries`
--

CREATE TABLE `orion_countries` (
  `id` mediumint(9) NOT NULL,
  `code` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `isdefault` tinyint(4) NOT NULL,
  `istax` tinyint(4) NOT NULL,
  `tax` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_countries`
--

INSERT INTO `orion_countries` (`id`, `code`, `country`, `isdefault`, `istax`, `tax`) VALUES
(1, 'AF', 'Afghanistan', 0, 2, 0),
(2, 'AL', 'Albania', 0, 2, 0),
(3, 'DZ', 'Algeria', 0, 2, 0),
(4, 'AS', 'American Samoa', 0, 2, 0),
(5, 'AD', 'Andorra', 0, 2, 0),
(6, 'AO', 'Angola', 0, 2, 0),
(7, 'AI', 'Anguilla', 0, 2, 0),
(8, 'AQ', 'Antarctica', 0, 2, 0),
(9, 'AG', 'Antigua and Barbuda', 0, 2, 0),
(10, 'AR', 'Argentina', 0, 2, 0),
(11, 'AM', 'Armenia', 0, 2, 0),
(12, 'AW', 'Aruba', 0, 2, 0),
(13, 'AU', 'Australia', 0, 2, 0),
(14, 'AT', 'Austria', 0, 2, 0),
(15, 'AZ', 'Azerbaijan', 0, 2, 0),
(16, 'BS', 'Bahamas', 0, 2, 0),
(17, 'BH', 'Bahrain', 0, 2, 0),
(18, 'BD', 'Bangladesh', 0, 2, 0),
(19, 'BB', 'Barbados', 0, 2, 0),
(20, 'BY', 'Belarus', 0, 2, 0),
(21, 'BE', 'Belgium', 0, 2, 0),
(22, 'BZ', 'Belize', 0, 2, 0),
(23, 'BJ', 'Benin', 0, 2, 0),
(24, 'BM', 'Bermuda', 0, 2, 0),
(25, 'BT', 'Bhutan', 0, 2, 0),
(26, 'BO', 'Bolivia', 0, 2, 0),
(27, 'BA', 'Bosnia and Herzegowina', 0, 2, 0),
(28, 'BW', 'Botswana', 0, 2, 0),
(29, 'BV', 'Bouvet Island', 0, 2, 0),
(30, 'BR', 'Brazil', 0, 2, 0),
(31, 'IO', 'British Indian Ocean Territory', 0, 2, 0),
(32, 'VG', 'British Virgin Islands', 0, 2, 0),
(33, 'BN', 'Brunei Darussalam', 0, 2, 0),
(34, 'BG', 'Bulgaria', 0, 2, 0),
(35, 'BF', 'Burkina Faso', 0, 2, 0),
(36, 'BI', 'Burundi', 0, 2, 0),
(37, 'KH', 'Cambodia', 0, 2, 0),
(38, 'CM', 'Cameroon', 0, 2, 0),
(39, 'CA', 'Canada', 0, 2, 0),
(40, 'CV', 'Cape Verde', 0, 2, 0),
(41, 'KY', 'Cayman Islands', 0, 2, 0),
(42, 'CF', 'Central African Republic', 0, 2, 0),
(43, 'TD', 'Chad', 0, 2, 0),
(44, 'CL', 'Chile', 0, 2, 0),
(45, 'CN', 'China', 0, 2, 0),
(46, 'CX', 'Christmas Island', 0, 2, 0),
(47, 'CC', 'Cocos (Keeling) Islands', 0, 2, 0),
(48, 'CO', 'Colombia', 0, 2, 0),
(49, 'KM', 'Comoros', 0, 2, 0),
(50, 'CG', 'Congo', 0, 2, 0),
(51, 'CK', 'Cook Islands', 0, 2, 0),
(52, 'CR', 'Costa Rica', 0, 2, 0),
(53, 'HR', 'Croatia', 0, 2, 0),
(54, 'CU', 'Cuba', 0, 2, 0),
(55, 'CY', 'Cyprus', 0, 2, 0),
(56, 'CZ', 'Czech Republic', 0, 2, 0),
(57, 'DK', 'Denmark', 0, 2, 0),
(58, 'DJ', 'Djibouti', 0, 2, 0),
(59, 'DM', 'Dominica', 0, 2, 0),
(60, 'DO', 'Dominican Republic', 0, 2, 0),
(61, 'TP', 'East Timor', 0, 2, 0),
(62, 'EC', 'Ecuador', 0, 2, 0),
(63, 'EG', 'Egypt', 0, 2, 0),
(64, 'SV', 'El Salvador', 0, 2, 0),
(65, 'GQ', 'Equatorial Guinea', 0, 2, 0),
(66, 'ER', 'Eritrea', 0, 2, 0),
(67, 'EE', 'Estonia', 0, 2, 0),
(68, 'ET', 'Ethiopia', 0, 2, 0),
(69, 'FK', 'Falkland Islands (Malvinas)', 0, 2, 0),
(70, 'FO', 'Faroe Islands', 0, 2, 0),
(71, 'FJ', 'Fiji', 0, 2, 0),
(72, 'FI', 'Finland', 0, 2, 0),
(73, 'FR', 'France', 0, 2, 0),
(74, 'FX', 'France, Metropolitan', 0, 2, 0),
(75, 'GF', 'French Guiana', 0, 2, 0),
(76, 'PF', 'French Polynesia', 0, 2, 0),
(77, 'TF', 'French Southern Territories', 0, 2, 0),
(78, 'GA', 'Gabon', 0, 2, 0),
(79, 'GM', 'Gambia', 0, 2, 0),
(80, 'GE', 'Georgia', 0, 2, 0),
(81, 'DE', 'Germany', 0, 2, 0),
(82, 'GH', 'Ghana', 0, 2, 0),
(83, 'GI', 'Gibraltar', 0, 2, 0),
(84, 'GR', 'Greece', 0, 2, 0),
(85, 'GL', 'Greenland', 0, 2, 0),
(86, 'GD', 'Grenada', 0, 2, 0),
(87, 'GP', 'Guadeloupe', 0, 2, 0),
(88, 'GU', 'Guam', 0, 2, 0),
(89, 'GT', 'Guatemala', 0, 2, 0),
(90, 'GN', 'Guinea', 0, 2, 0),
(91, 'GW', 'Guinea-Bissau', 0, 2, 0),
(92, 'GY', 'Guyana', 0, 2, 0),
(93, 'HT', 'Haiti', 0, 2, 0),
(94, 'HM', 'Heard and McDonald Islands', 0, 2, 0),
(95, 'HN', 'Honduras', 0, 2, 0),
(96, 'HK', 'Hong Kong', 0, 2, 0),
(97, 'HU', 'Hungary', 0, 2, 0),
(98, 'IS', 'Iceland', 0, 2, 0),
(99, 'IN', 'India', 0, 2, 0),
(100, 'ID', 'Indonesia', 0, 2, 0),
(101, 'IQ', 'Iraq', 0, 2, 0),
(102, 'IE', 'Ireland', 0, 2, 0),
(103, 'IR', 'Islamic Republic of Iran', 0, 2, 0),
(104, 'IL', 'Israel', 0, 2, 0),
(105, 'IT', 'Italy', 0, 2, 0),
(106, 'JM', 'Jamaica', 0, 2, 0),
(107, 'JP', 'Japan', 0, 2, 0),
(108, 'JO', 'Jordan', 0, 2, 0),
(109, 'KZ', 'Kazakhstan', 0, 2, 0),
(110, 'KE', 'Kenya', 0, 2, 0),
(111, 'KI', 'Kiribati', 0, 2, 0),
(112, 'KP', 'Korea', 0, 2, 0),
(113, 'KR', 'Korea, Republic of', 0, 2, 0),
(114, 'KW', 'Kuwait', 0, 2, 0),
(115, 'KG', 'Kyrgyzstan', 0, 2, 0),
(116, 'LA', 'Laos', 0, 2, 0),
(117, 'LV', 'Latvia', 0, 2, 0),
(118, 'LB', 'Lebanon', 0, 2, 0),
(119, 'LS', 'Lesotho', 0, 2, 0),
(120, 'LR', 'Liberia', 0, 2, 0),
(121, 'LY', 'Libyan Arab Jamahiriya', 0, 2, 0),
(122, 'LI', 'Liechtenstein', 0, 2, 0),
(123, 'LT', 'Lithuania', 0, 2, 0),
(124, 'LU', 'Luxembourg', 0, 2, 0),
(125, 'MO', 'Macau', 0, 2, 0),
(126, 'MK', 'Macedonia', 0, 2, 0),
(127, 'MG', 'Madagascar', 0, 2, 0),
(128, 'MW', 'Malawi', 0, 2, 0),
(129, 'MY', 'Malaysia', 0, 2, 0),
(130, 'MV', 'Maldives', 0, 2, 0),
(131, 'ML', 'Mali', 0, 2, 0),
(132, 'MT', 'Malta', 0, 2, 0),
(133, 'MH', 'Marshall Islands', 0, 2, 0),
(134, 'MQ', 'Martinique', 0, 2, 0),
(135, 'MR', 'Mauritania', 0, 2, 0),
(136, 'MU', 'Mauritius', 0, 2, 0),
(137, 'YT', 'Mayotte', 0, 2, 0),
(138, 'MX', 'Mexico', 0, 2, 0),
(139, 'FM', 'Micronesia', 0, 2, 0),
(140, 'MD', 'Moldova, Republic of', 0, 2, 0),
(141, 'MC', 'Monaco', 0, 2, 0),
(142, 'MN', 'Mongolia', 0, 2, 0),
(143, 'MS', 'Montserrat', 0, 2, 0),
(144, 'MA', 'Morocco', 0, 2, 0),
(145, 'MZ', 'Mozambique', 0, 2, 0),
(146, 'MM', 'Myanmar', 0, 2, 0),
(147, 'NA', 'Namibia', 0, 2, 0),
(148, 'NR', 'Nauru', 0, 2, 0),
(149, 'NP', 'Nepal', 0, 2, 0),
(150, 'NL', 'Netherlands', 0, 2, 0),
(151, 'AN', 'Netherlands Antilles', 0, 2, 0),
(152, 'NC', 'New Caledonia', 0, 2, 0),
(153, 'NZ', 'New Zealand', 0, 2, 0),
(154, 'NI', 'Nicaragua', 0, 2, 0),
(155, 'NE', 'Niger', 0, 2, 0),
(156, 'NG', 'Nigeria', 0, 2, 0),
(157, 'NU', 'Niue', 0, 2, 0),
(158, 'NF', 'Norfolk Island', 0, 2, 0),
(159, 'MP', 'Northern Mariana Islands', 0, 2, 0),
(160, 'NO', 'Norway', 0, 2, 0),
(161, 'OM', 'Oman', 0, 2, 0),
(162, 'PK', 'Pakistan', 0, 2, 0),
(163, 'PW', 'Palau', 0, 2, 0),
(164, 'PA', 'Panama', 0, 2, 0),
(165, 'PG', 'Papua New Guinea', 0, 2, 0),
(166, 'PY', 'Paraguay', 0, 2, 0),
(167, 'PE', 'Peru', 0, 2, 0),
(168, 'PH', 'Philippines', 0, 2, 0),
(169, 'PN', 'Pitcairn', 0, 2, 0),
(170, 'PL', 'Poland', 0, 2, 0),
(171, 'PT', 'Portugal', 0, 2, 0),
(172, 'PR', 'Puerto Rico', 0, 2, 0),
(173, 'QA', 'Qatar', 0, 2, 0),
(174, 'RE', 'Reunion', 0, 2, 0),
(175, 'RO', 'Romania', 0, 2, 0),
(176, 'RU', 'Russian Federation', 0, 2, 0),
(177, 'RW', 'Rwanda', 0, 2, 0),
(178, 'LC', 'Saint Lucia', 0, 2, 0),
(179, 'WS', 'Samoa', 0, 2, 0),
(180, 'SM', 'San Marino', 0, 2, 0),
(181, 'ST', 'Sao Tome and Principe', 0, 2, 0),
(182, 'SA', 'Saudi Arabia', 0, 2, 0),
(183, 'SN', 'Senegal', 0, 2, 0),
(184, 'YU', 'Serbia and Montenegro', 0, 2, 0),
(185, 'SC', 'Seychelles', 0, 2, 0),
(186, 'SL', 'Sierra Leone', 0, 2, 0),
(187, 'SG', 'Singapore', 0, 2, 0),
(188, 'SK', 'Slovakia', 0, 2, 0),
(189, 'SI', 'Slovenia', 0, 2, 0),
(190, 'SB', 'Solomon Islands', 0, 2, 0),
(191, 'SO', 'Somalia', 0, 2, 0),
(192, 'ZA', 'South Africa', 0, 2, 0),
(193, 'ES', 'Spain', 0, 2, 0),
(194, 'LK', 'Sri Lanka', 0, 2, 0),
(195, 'SH', 'St. Helena', 0, 2, 0),
(196, 'KN', 'St. Kitts and Nevis', 0, 2, 0),
(197, 'PM', 'St. Pierre and Miquelon', 0, 2, 0),
(198, 'VC', 'St. Vincent and the Grenadines', 0, 2, 0),
(199, 'SD', 'Sudan', 0, 2, 0),
(200, 'SR', 'Suriname', 0, 2, 0),
(201, 'SJ', 'Svalbard and Jan Mayen Islands', 0, 2, 0),
(202, 'SZ', 'Swaziland', 0, 2, 0),
(203, 'SE', 'Sweden', 0, 2, 0),
(204, 'CH', 'Switzerland', 0, 2, 0),
(205, 'SY', 'Syrian Arab Republic', 0, 2, 0),
(206, 'TW', 'Taiwan', 0, 2, 0),
(207, 'TJ', 'Tajikistan', 0, 2, 0),
(208, 'TZ', 'Tanzania, United Republic of', 0, 2, 0),
(209, 'TH', 'Thailand', 0, 2, 0),
(210, 'TG', 'Togo', 0, 2, 0),
(211, 'TK', 'Tokelau', 0, 2, 0),
(212, 'TO', 'Tonga', 0, 2, 0),
(213, 'TT', 'Trinidad and Tobago', 0, 2, 0),
(214, 'TN', 'Tunisia', 0, 2, 0),
(215, 'TR', 'Türkiye', 1, 2, 0),
(216, 'TM', 'Turkmenistan', 0, 2, 0),
(217, 'TC', 'Turks and Caicos Islands', 0, 2, 0),
(218, 'TV', 'Tuvalu', 0, 2, 0),
(219, 'UG', 'Uganda', 0, 2, 0),
(220, 'UA', 'Ukraine', 0, 2, 0),
(221, 'AE', 'United Arab Emirates', 0, 2, 0),
(222, 'GB', 'United Kingdom (Great Britain)', 0, 2, 0),
(223, 'US', 'ABD', 0, 1, 16),
(224, 'VI', 'United States Virgin Islands', 0, 2, 0),
(225, 'UY', 'Uruguay', 0, 2, 0),
(226, 'UZ', 'Uzbekistan', 0, 2, 0),
(227, 'VU', 'Vanuatu', 0, 2, 0),
(228, 'VA', 'Vatican City State', 0, 2, 0),
(229, 'VE', 'Venezuela', 0, 2, 0),
(230, 'VN', 'Vietnam', 0, 2, 0),
(231, 'WF', 'Wallis And Futuna Islands', 0, 2, 0),
(232, 'EH', 'Western Sahara', 0, 2, 0),
(233, 'YE', 'Yemen', 0, 2, 0),
(234, 'ZR', 'Zaire', 0, 2, 0),
(235, 'ZM', 'Zambia', 0, 2, 0),
(236, 'ZW', 'Zimbabwe', 0, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orion_faqs`
--

CREATE TABLE `orion_faqs` (
  `faqid` int(11) NOT NULL,
  `faqtitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `faqdetail` text COLLATE utf8_unicode_ci,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_faqs`
--

INSERT INTO `orion_faqs` (`faqid`, `faqtitle`, `faqdetail`, `isdeleted`, `dtcreated`) VALUES
(1, 'Integer sodales hendrerit nunc ut sodales?', '<p>Integer sodales hendrerit nunc ut sodales! Nunc vitae tortor ut elit facilisis fringilla. Cras enim enim, tristique et pretium ut, dapibus eu nisi. Phasellus diam tellus, faucibus vitae blandit id, fringilla a ante! Sed non nunc ac est porttitor aliquet non et risus. Phasellus gravida feugiat imperdiet! Aenean placerat imperdiet eros, id bibendum erat imperdiet quis. Suspendisse varius, nulla vitae commodo aliquet, eros nunc consequat eros; at sodales felis lectus sed lectus. Nunc sed nunc eros, ac placerat tellus.<br /><br />Pellentesque in dignissim nunc. Nullam ullamcorper pulvinar libero; accumsan commodo mi dictum nec. Etiam feugiat neque lorem! Aliquam adipiscing sollicitudin iaculis. Phasellus aliquam pretium elementum. Suspendisse potenti. Ut fringilla pretium massa nec tempus. Ut ut nulla tristique lacus bibendum egestas. Duis accumsan pharetra purus, et sollicitudin enim ullamcorper at. Pellentesque turpis enim; fermentum id pharetra at, tristique bibendum arcu. Cras dui nibh, venenatis a blandit nec, vehicula a quam.<br /><br />Morbi auctor massa a tellus pulvinar convallis. Cras pellentesque elit rutrum neque rhoncus pharetra. In tempus sollicitudin dui, faucibus viverra augue pretium nec. Suspendisse pretium blandit porttitor. Sed ornare sem non mi placerat aliquet. Morbi vehicula risus fringilla velit gravida vitae aliquet lacus gravida. Proin venenatis, augue ut auctor faucibus, sem erat laoreet turpis, eget eleifend massa erat ut augue. Nullam sit amet augue accumsan ante rhoncus malesuada nec ut odio? Maecenas convallis eros eget sapien malesuada sit amet lacinia enim pellentesque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam tincidunt, lectus et volutpat suscipit, libero tortor blandit leo, sit amet ornare risus quam ac magna. Nunc convallis purus sed sapien facilisis luctus.<br /><br /></p>\r\n<p>&nbsp;</p>', NULL, '2012-03-17 06:48:13'),
(2, 'Aenean vitae tortor arcu, at ornare nisi.', '<p><strong></strong>Nullam fermentum orci vitae massa tincidunt ut convallis neque tempor. Cras iaculis ultricies risus, vitae commodo ipsum tempus at? Pellentesque dictum dui urna, sed imperdiet tellus. <span style=\"text-decoration: underline;\"><em>Nullam rutrum, urna vitae</em> </span>laoreet convallis; nisi lectus sagittis orci, in ornare lectus lacus eu sapien. Nunc ante eros, pharetra ac pretium et, molestie sit amet magna. Duis ac ligula eget diam ullamcorper viverra sed non urna. Duis pellentesque ornare elit quis condimentum. Etiam eleifend aliquam lectus, non rutrum dolor vulputate quis. Donec non felis urna, non posuere libero. Praesent sagittis, nisi eu facilisis pharetra, nisl sem feugiat diam, vitae dapibus enim tellus fringilla nisi. In ultrices urna non mi lobortis vel hendrerit felis posuere. Sed et augue nec diam accumsan mattis. Fusce pellentesque mi semper turpis sagittis viverra.</p>', NULL, '2012-04-13 09:35:56');

-- --------------------------------------------------------

--
-- Table structure for table `orion_files`
--

CREATE TABLE `orion_files` (
  `fileid` int(9) UNSIGNED NOT NULL,
  `fkid` int(9) UNSIGNED DEFAULT '0',
  `filename` varchar(36) COLLATE utf8_turkish_ci NOT NULL,
  `origname` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `type` enum('firm') COLLATE utf8_turkish_ci DEFAULT NULL,
  `isonline` tinyint(4) DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `orion_files`
--

INSERT INTO `orion_files` (`fileid`, `fkid`, `filename`, `origname`, `type`, `isonline`, `isdeleted`, `dtcreated`) VALUES
(3, NULL, 'DnzyNrtrXPw8bbzSkDtTaFSTfMu9tMrZ.zip', 'emre_acilan_banner_v2.zip', 'firm', NULL, 0, '2012-03-17 09:33:11'),
(4, NULL, 'PVSxCB4xAzVXM9zeWRY4v9xcke9skHET.zip', 'Bookmark PDF1-Customize Acrobat Using JavaScript.zip', 'firm', NULL, 1, '2012-03-17 09:33:44'),
(5, NULL, 'nHaTKwFeNCuHNpFkXs2h4CHWkRw3mKh8.xls', 'timeline.xls', 'firm', NULL, 1, '2012-03-17 09:34:55');

-- --------------------------------------------------------

--
-- Table structure for table `orion_globals`
--

CREATE TABLE `orion_globals` (
  `globalid` int(11) NOT NULL,
  `tag` varchar(30) COLLATE utf8_unicode_ci DEFAULT '',
  `tagproperty` varchar(30) COLLATE utf8_unicode_ci DEFAULT '',
  `tagvalue` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_globals`
--

INSERT INTO `orion_globals` (`globalid`, `tag`, `tagproperty`, `tagvalue`) VALUES
(1, 'newsletter', 'template', 'a:2:{s:18:\"newsletter_subject\";s:46:\"Maecenas sed purus turpis, id fermentum lacus.\";s:15:\"newsletter_body\";s:2243:\"<p>Maecenas sed purus turpis, id fermentum lacus. Duis nec ligula in quam aliquet bibendum id in enim. Duis placerat ligula id quam interdum dignissim. Nam accumsan ullamcorper leo a scelerisque! Vivamus dictum accumsan nulla, sit amet venenatis enim placerat at. Quisque aliquet orci non felis dignissim condimentum. Maecenas ut ante id tellus rutrum molestie id at enim. Etiam vitae lectus diam, vel tristique nulla. Vestibulum a tellus et leo hendrerit suscipit id vitae purus. Praesent dictum eros quis quam adipiscing at mattis urna consectetur! In pretium mauris non mi vehicula id pulvinar dui vehicula. Quisque fringilla tortor eu ipsum vulputate eget egestas orci tristique. Pellentesque id neque ac lacus viverra venenatis sed et urna?<br /><br />Suspendisse molestie dignissim lorem. Duis ante mi, lacinia malesuada ultrices id, sodales vel enim. Integer in tellus tortor, eu adipiscing ante. Vestibulum nisl nisi, placerat at commodo ac, facilisis eu augue. Morbi aliquet odio eu erat dignissim euismod at sed ligula. Curabitur molestie dolor auctor enim cursus quis hendrerit diam luctus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in enim eget purus condimentum fringilla. In hac habitasse platea dictumst. Nam quis sem tristique ante sagittis tincidunt ac vitae orci. Nulla lectus mi, egestas ut consequat non, posuere in ipsum. Pellentesque tempus hendrerit aliquam. Praesent quis erat magna. Sed accumsan egestas odio id consequat. Donec enim elit; ullamcorper et ullamcorper facilisis, vehicula nec neque. Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br /><br />Duis sed felis tellus, non hendrerit massa. Vestibulum facilisis tempus libero, et ornare lectus eleifend ut. Phasellus ac orci nec nunc congue sollicitudin non ut nisl. Etiam posuere, lectus vel gravida aliquam, nulla tellus pharetra nunc, a blandit nulla turpis et nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer iaculis, augue at feugiat sollicitudin, orci augue ultrices nulla, eu scelerisque enim massa eu massa? Sed non velit magna. Fusce sed nulla ac ante tempor condimentum. Nulla facilisi. In nec tortor lacus. Cras vulputate iaculis posuere.<br /><br /></p>\";}'),
(3, 'settings', 'theme', 'a:13:{s:11:\"description\";s:11:\"description\";s:8:\"keywords\";s:15:\"one, two, three\";s:4:\"logo\";s:8:\"3acz.png\";s:5:\"title\";s:30:\"Orion E-commerce Software Demo\";s:8:\"sitename\";s:5:\"Orion\";s:6:\"slogan\";s:19:\"Per aspera ad astra\";s:3:\"tos\";s:591:\"<p><strong>Terms of Usage </strong></p>\r\n<p>Nulla facilisi. Curabitur et purus quam, pellentesque molestie ligula. Maecenas at leo eget ipsum aliquam imperdiet eleifend vel nisl. Vestibulum bibendum porttitor elit, id fringilla mauris aliquet at. Nullam at dui quis nibh varius gravida? Sed tempor euismod fringilla. Phasellus at justo venenatis erat elementum condimentum. Curabitur ultricies mollis odio vitae venenatis. Suspendisse potenti. Sed non condimentum elit. Cras consectetur feugiat augue vitae elementum! Suspendisse potenti. Praesent tristique accumsan aliquam.<br /><br /></p>\";s:8:\"accounts\";s:582:\"<p><strong>Bank/Payment Accounts </strong></p>\r\n<p>Curabitur et purus quam, pellentesque molestie ligula. Maecenas at leo eget ipsum aliquam imperdiet eleifend vel nisl. Vestibulum bibendum porttitor elit, id fringilla mauris aliquet at. Nullam at dui quis nibh varius gravida? Sed tempor euismod fringilla. Phasellus at justo venenatis erat elementum condimentum. Curabitur ultricies mollis odio vitae venenatis. Suspendisse potenti. Sed non condimentum elit. Cras consectetur feugiat augue vitae elementum! Suspendisse potenti. Praesent tristique accumsan aliquam.<br /><br /></p>\";s:7:\"aboutus\";s:574:\"<p><strong>About Us<br /></strong></p>\r\n<p>Curabitur et purus quam, pellentesque molestie ligula. Maecenas at leo eget ipsum aliquam imperdiet eleifend vel nisl. Vestibulum bibendum porttitor elit, id fringilla mauris aliquet at. Nullam at dui quis nibh varius gravida? Sed tempor euismod fringilla. Phasellus at justo venenatis erat elementum condimentum. Curabitur ultricies mollis odio vitae venenatis. Suspendisse potenti. Sed non condimentum elit. Cras consectetur feugiat augue vitae elementum! Suspendisse potenti. Praesent tristique accumsan aliquam.<br /><br /></p>\";s:9:\"guarantee\";s:510:\"<p><strong>Guarantee Policy </strong></p>\r\n<p>Maecenas at leo eget ipsum aliquam imperdiet eleifend vel nisl. Vestibulum bibendum porttitor elit, id fringilla mauris aliquet at. Nullam at dui quis nibh varius gravida? Sed tempor euismod fringilla. Phasellus at justo venenatis erat elementum condimentum. Curabitur ultricies mollis odio vitae venenatis. Suspendisse potenti. Sed non condimentum elit. Cras consectetur feugiat augue vitae elementum! Suspendisse potenti. Praesent tristique accumsan aliquam.</p>\";s:9:\"refunding\";s:525:\"<p><strong>Refunding Policy </strong></p>\r\n<p>Maecenas at leo eget ipsum aliquam imperdiet eleifend vel nisl. Vestibulum bibendum porttitor elit, id fringilla mauris aliquet at. Nullam at dui quis nibh varius gravida? Sed tempor euismod fringilla. Phasellus at justo venenatis erat elementum condimentum. Curabitur ultricies mollis odio vitae venenatis. Suspendisse potenti. Sed non condimentum elit. Cras consectetur feugiat augue vitae elementum! Suspendisse potenti. Praesent tristique accumsan aliquam.</p>\r\n<p>&nbsp;</p>\";s:7:\"address\";s:78:\"<p>Address: <em>Maecenas at leo eget ipsum aliquam imperdiet eleifend</em></p>\";s:5:\"theme\";s:7:\"default\";}');

-- --------------------------------------------------------

--
-- Table structure for table `orion_loginstrikes`
--

CREATE TABLE `orion_loginstrikes` (
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dtcreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_loginstrikes`
--

INSERT INTO `orion_loginstrikes` (`username`, `dtcreated`) VALUES
('uzaytek', '2012-03-26 15:13:56'),
('demo', '2012-04-19 14:15:47'),
('demo', '2012-04-25 13:19:43'),
('demo', '2012-05-04 17:02:05'),
('test', '2012-05-04 17:02:12'),
('test', '2012-05-04 17:02:32'),
('test', '2012-05-04 17:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `orion_logs`
--

CREATE TABLE `orion_logs` (
  `logid` int(9) UNSIGNED NOT NULL,
  `logvalue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dtcreated` datetime NOT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_logs`
--

INSERT INTO `orion_logs` (`logid`, `logvalue`, `dtcreated`, `isdeleted`) VALUES
(1, '1337278899:/orion/detail.php::No value has been supplied for decryption', '2012-05-17 21:21:39', NULL),
(2, '1337441385:/orion/slides.php::No value has been supplied for decryption', '2012-05-19 18:29:46', NULL),
(3, '1337815054:/orion/detail.php::No value has been supplied for decryption', '2012-05-24 02:17:34', NULL),
(4, '1338149003:/orion/slides.php::No value has been supplied for decryption', '2012-05-27 23:03:24', NULL),
(5, '1338749797:/orion/slides.php::No value has been supplied for decryption', '2012-06-03 21:56:38', NULL),
(6, '1344706032:/orion/detail.php::No value has been supplied for decryption', '2012-08-11 20:27:12', NULL),
(7, '1362965300:/orion/detail.php::No value has been supplied for decryption', '2013-03-11 03:28:20', NULL),
(8, '1365051521:/orion/slides.php::No value has been supplied for decryption', '2013-04-04 07:58:41', NULL),
(9, '1365221499:/orion/slides.php::No value has been supplied for decryption', '2013-04-06 07:11:39', NULL),
(10, '1365517476:/orion/detail.php::No value has been supplied for decryption', '2013-04-09 17:24:37', NULL),
(11, '1365879197:/orion/slides.php::No value has been supplied for decryption', '2013-04-13 21:53:17', NULL),
(12, '1366126581:/orion/detail.php::No value has been supplied for decryption', '2013-04-16 18:36:21', NULL),
(13, '1366152004:/orion/slides.php:id=cRBRJPfdJ3Qb7h1dcOyc8gFTOrcM0ZVt%20onclick=:Source string contains an invalid character ( )', '2013-04-17 01:40:04', NULL),
(14, '1366152007:/orion/slides.php:id=dBTN5wNeKqFApy6rtsId2yM4ubeIdHwn%20onclick=:Source string contains an invalid character ( )', '2013-04-17 01:40:08', NULL),
(15, '1366152010:/orion/slides.php:id=eGHJ0krvLcMY4m0niume4k8fPgf8n9Fh%20onclick=:Source string contains an invalid character ( )', '2013-04-17 01:40:10', NULL),
(16, '1368522099:/orion/slides.php::No value has been supplied for decryption', '2013-05-14 12:01:39', NULL),
(17, '1369651909:/orion/detail.php::No value has been supplied for decryption', '2013-05-27 13:51:49', NULL),
(18, '1370728266:/orion/slides.php:id=cRBRJPfdJ3Qb7h1dcOyc8gFTOrcM0ZVt\':Source string contains an invalid character (\')', '2013-06-09 00:51:06', NULL),
(19, '1370949945:/orion/detail.php::No value has been supplied for decryption', '2013-06-11 14:25:45', NULL),
(20, '1371366415:/orion/slides.php::No value has been supplied for decryption', '2013-06-16 10:06:55', NULL),
(21, '1371555660:/orion/slides.php::No value has been supplied for decryption', '2013-06-18 14:41:00', NULL),
(22, '1372868438:/orion/slides.php:id=cRBRJPfdJ3Qb7h1dcOyc8gFTOrcM0ZVt%20onclick=:Source string contains an invalid character ( )', '2013-07-03 19:20:38', NULL),
(23, '1372868443:/orion/slides.php:id=dBTN5wNeKqFApy6rtsId2yM4ubeIdHwn%20onclick=:Source string contains an invalid character ( )', '2013-07-03 19:20:43', NULL),
(24, '1372868450:/orion/slides.php:id=eGHJ0krvLcMY4m0niume4k8fPgf8n9Fh%20onclick=:Source string contains an invalid character ( )', '2013-07-03 19:20:50', NULL),
(25, '1374128518:/orion/slides.php::No value has been supplied for decryption', '2013-07-18 09:21:58', NULL),
(26, '1374495862:/orion/slides.php::No value has been supplied for decryption', '2013-07-22 15:24:23', NULL),
(27, '1374680165:/orion/slides.php::No value has been supplied for decryption', '2013-07-24 18:36:06', NULL),
(28, '1375382715:/orion/detail.php::No value has been supplied for decryption', '2013-08-01 21:45:15', NULL),
(29, '1377526934:/orion/slides.php::No value has been supplied for decryption', '2013-08-26 17:22:15', NULL),
(30, '1378372354:/orion/detail.php::No value has been supplied for decryption', '2013-09-05 12:12:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_news`
--

CREATE TABLE `orion_news` (
  `newsid` int(11) NOT NULL,
  `newstitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `newsdetail` text COLLATE utf8_unicode_ci,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_news`
--

INSERT INTO `orion_news` (`newsid`, `newstitle`, `newsdetail`, `isdeleted`, `dtcreated`) VALUES
(1, 'Nullam ac risus ut magna interdum sodales.', '<p>Nullam ac risus ut magna interdum sodales. Maecenas et nibh aliquam enim aliquet adipiscing. Sed eget risus enim, non egestas felis. Nam dapibus lorem nec nulla facilisis interdum. Etiam rhoncus metus non odio viverra auctor! Vestibulum vel massa risus, sed accumsan tellus. Duis enim tellus, tincidunt non sollicitudin sed, adipiscing vel massa. Suspendisse euismod elit ac turpis feugiat fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non nunc a mi pretium ullamcorper tempor sit amet ante! In hac habitasse platea dictumst. Aliquam in nisi purus. Donec molestie venenatis euismod! Curabitur rhoncus, orci nec egestas condimentum, diam urna scelerisque nibh, in euismod quam elit ac arcu.<br /><br /></p>', NULL, '2012-03-14 12:05:13'),
(2, 'adsfadsa 1', 'dsfadfadsf 1', 1, '2012-03-14 12:05:28'),
(3, 'Mauris id nisi in tortor ullamcorper posuere.', '<p>Mauris id nisi in tortor ullamcorper posuere. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In est est, sagittis a rhoncus eget, vulputate sed lectus. Praesent et sem eget odio condimentum cursus. Ut et eleifend est? Praesent pulvinar mattis lacus non accumsan? Donec blandit dignissim commodo! Mauris orci ipsum, aliquam eget cursus nec, sollicitudin eu velit? Sed a quam ut dui porttitor blandit.<br /><br /></p>\r\n<p>&nbsp;</p>', NULL, '2012-03-15 14:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `orion_newsletters`
--

CREATE TABLE `orion_newsletters` (
  `letterid` int(11) NOT NULL,
  `lettersubject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `letterbody` text COLLATE utf8_unicode_ci,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orion_paymentaccounts`
--

CREATE TABLE `orion_paymentaccounts` (
  `accountid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `detail` text COLLATE utf8_unicode_ci,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_paymentaccounts`
--

INSERT INTO `orion_paymentaccounts` (`accountid`, `title`, `detail`, `isdeleted`, `dtcreated`) VALUES
(4, 'XYZ Bank Account EFT', 'XYZ Bank QWERTY OFFICE \r\n123456 Account Office', NULL, '2012-03-28 11:29:54'),
(5, 'Post Check', 'Post Check Number: 566321', NULL, '2012-05-01 07:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `orion_paymentgateways`
--

CREATE TABLE `orion_paymentgateways` (
  `gateid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `passwd` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `clientid` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `testurl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `realurl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `istestmode` tinyint(4) DEFAULT NULL,
  `isactive` tinyint(4) DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_paymentgateways`
--

INSERT INTO `orion_paymentgateways` (`gateid`, `title`, `username`, `passwd`, `clientid`, `testurl`, `realurl`, `istestmode`, `isactive`, `isdeleted`, `dtcreated`) VALUES
(4, 'With Credit Card [ABC Bank]', 'apiclerk', 'clerkpassword', 'customer client id', 'https://test.abc.com/servlet/cc5ApiServer', 'https://ccpos.abc.com/servlet/cc5ApiServer', 0, 1, NULL, '2012-03-28 08:11:11'),
(5, 'XWYZ Bank CCard', 'xwyz username', 'pass', 'clintid', 'test.xwyz.bank.ccard', 'www.xwyz.bank.ccard', 1, 0, NULL, '2012-05-01 06:55:44');

-- --------------------------------------------------------

--
-- Table structure for table `orion_payments`
--

CREATE TABLE `orion_payments` (
  `paymentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `paymenttype` tinyint(4) NOT NULL,
  `typeid` int(11) NOT NULL,
  `typetitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentkey` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `paymentstatus` tinyint(4) NOT NULL,
  `orderstatus` tinyint(4) NOT NULL,
  `total` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `paymentowner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccardnumber` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentdate` datetime DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentnote` text COLLATE utf8_unicode_ci,
  `dtcreated` datetime NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  `dtdeleted` datetime DEFAULT NULL,
  `dtmodified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orion_productimages`
--

CREATE TABLE `orion_productimages` (
  `imgid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `hostfilename` varchar(30) COLLATE utf8_unicode_ci DEFAULT '',
  `realfilename` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `fileextension` varchar(5) COLLATE utf8_unicode_ci DEFAULT '',
  `filewh` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `isdefault` tinyint(4) DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `dtcreated` datetime NOT NULL,
  `dtdeleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_productimages`
--

INSERT INTO `orion_productimages` (`imgid`, `productid`, `hostfilename`, `realfilename`, `fileextension`, `filewh`, `isdefault`, `isdeleted`, `dtcreated`, `dtdeleted`) VALUES
(1, 1, 'hx9ancrs7k', '01.jpg', '.jpg', '81,156', 0, 1, '2012-05-05 13:14:43', '2012-05-05 13:20:41'),
(2, 2, 'u3vtz5vpv8', '02.jpg', '.jpg', '79,160', 0, 1, '2012-05-05 13:16:49', '2012-05-05 13:22:05'),
(3, 1, 'ye5wtcvfc9', '01.jpg', '.jpg', '800,600', 1, NULL, '2012-05-05 13:20:47', NULL),
(4, 2, 'mbzb5h8s4c', '02.jpg', '.jpg', '535,616', 1, NULL, '2012-05-05 13:22:12', NULL),
(5, 2, 'saffr2uunp', '02.2.jpg', '.jpg', '440,330', 0, NULL, '2012-05-05 13:24:24', NULL),
(6, 1, 'xzz66mrz7u', '01.2.jpg', '.jpg', '400,300', 0, NULL, '2012-05-05 13:26:25', NULL),
(7, 3, 'eb9d3kpt7x', 'apple-iphone-profile.png', '.png', '300,450', 1, NULL, '2012-05-07 12:52:56', NULL),
(8, 3, 'yp3ynrnwwh', 'iphone 3G.jpg', '.jpg', '362,340', 0, NULL, '2012-05-07 12:53:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_products`
--

CREATE TABLE `orion_products` (
  `productid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `productname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `productdetail` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `stockcount` smallint(6) NOT NULL DEFAULT '0',
  `oldstockcount` smallint(6) NOT NULL DEFAULT '0',
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `campaignprice` double(10,4) DEFAULT NULL,
  `urlhandler` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `isanewone` tinyint(4) DEFAULT NULL,
  `isapromote` tinyint(4) DEFAULT NULL,
  `isaheadline` tinyint(4) DEFAULT NULL,
  `isabestseller` tinyint(4) DEFAULT NULL,
  `dtcreated` datetime NOT NULL,
  `dtcampaignstart` datetime DEFAULT NULL,
  `dtcampaignstop` datetime DEFAULT NULL,
  `dtmodified` datetime DEFAULT NULL,
  `dtdeleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_products`
--

INSERT INTO `orion_products` (`productid`, `catid`, `productname`, `productdetail`, `stockcount`, `oldstockcount`, `price`, `campaignprice`, `urlhandler`, `isdeleted`, `isanewone`, `isapromote`, `isaheadline`, `isabestseller`, `dtcreated`, `dtcampaignstart`, `dtcampaignstop`, `dtmodified`, `dtdeleted`) VALUES
(1, 3, 'Sony Ericcson W705', '<p>Sony Ericcson W705</p>', 0, 0, '99.5500', 0.0000, NULL, NULL, 1, 1, 1, 1, '2012-05-05 13:14:16', '2012-05-12 15:32:29', '2012-05-12 15:32:29', '2012-05-12 15:32:29', NULL),
(2, 4, 'HTC Touch Diamond2', '<p>HTC Touch Diamond2</p>', 0, 0, '399.9500', 0.0000, NULL, NULL, 1, 1, 1, 1, '2012-05-05 13:16:26', '2012-05-12 15:32:20', '2012-05-12 15:32:20', '2012-05-12 15:32:20', NULL),
(3, 8, 'Apple Iphone', '<p><strong>Phasellus vulputate interdum placerat. Morbi id magna lectus!</strong> Sed laoreet egestas arcu at dignissim? Donec sed libero justo, ac ultrices risus. Nullam tempor, arcu non placerat aliquam, orci ipsum aliquet ante, sollicitudin sodales nul', 0, 0, '980.0000', 900.0000, NULL, NULL, NULL, 1, 1, 1, '2012-05-07 12:49:07', '2012-05-04 00:00:00', '2012-05-18 00:00:00', '2012-05-12 15:32:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_producttags`
--

CREATE TABLE `orion_producttags` (
  `productid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_producttags`
--

INSERT INTO `orion_producttags` (`productid`, `tagid`) VALUES
(3, 10),
(3, 11),
(2, 7),
(2, 8),
(2, 9),
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `orion_quemail`
--

CREATE TABLE `orion_quemail` (
  `queid` int(11) NOT NULL,
  `mailto` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `mailsubject` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `mailbody` text COLLATE utf8_unicode_ci,
  `issent` tinyint(4) DEFAULT NULL,
  `dtcreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `extradata` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_quemail`
--

INSERT INTO `orion_quemail` (`queid`, `mailto`, `mailsubject`, `mailbody`, `issent`, `dtcreated`, `extradata`) VALUES
(4, '1', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:47:55', NULL),
(5, '2', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:47:55', NULL),
(6, 'aydin.uzun@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:47:56', NULL),
(7, '1', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:50:42', NULL),
(8, '2', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:50:42', NULL),
(9, 'a', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:50:42', NULL),
(10, 'u', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:51:34', NULL),
(11, 'a', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:51:34', NULL),
(12, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:52:03', NULL),
(13, 'a', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:52:03', NULL),
(14, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:52:39', NULL),
(15, 'a', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:52:39', NULL),
(16, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:53:11', NULL),
(17, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:54:54', NULL),
(18, 'aydin.uzun@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:54:54', NULL),
(19, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:57:15', NULL),
(20, 'aydin.uzun@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:57:15', NULL),
(21, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:57:59', NULL),
(22, 'aydin.uzun@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:57:59', NULL),
(23, 'uzaytek@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:59:10', NULL),
(24, 'aydin.uzun@gmail.com', 'hebele 2', '<p>h&uuml;bele 2</p>', 0, '2012-03-17 08:59:10', NULL),
(25, 'uzaytek@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=product&itemid=bYO0Sigg1itM9idS5kubqFir5ymcU6Bu', 0, '2012-04-26 09:31:28', NULL),
(26, 'uzaytek+test@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=product&itemid=bYO0Sigg1itM9idS5kubqFir5ymcU6Bu', 0, '2012-04-26 09:31:28', NULL),
(27, 'uzaytek@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=cat&itemid=3', 0, '2012-04-26 09:35:59', NULL),
(28, 'uzaytek+test@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=cat&itemid=3', 0, '2012-04-26 09:35:59', NULL),
(29, 'uzaytek@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=cat&itemid=3', 0, '2012-04-26 09:40:17', NULL),
(30, 'uzaytek+test@gmail.com', 'Product recommend to you from Aydin Uzun', 'Share a link with you from Aydin Uzun\n\n/orion/share.php?itemtype=cat&itemid=3', 0, '2012-04-26 09:40:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_sessions`
--

CREATE TABLE `orion_sessions` (
  `sid` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orion_stats`
--

CREATE TABLE `orion_stats` (
  `itemid` int(11) NOT NULL,
  `stattype` tinyint(4) DEFAULT NULL,
  `useraction` tinyint(4) DEFAULT NULL,
  `dtcreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_stats`
--

INSERT INTO `orion_stats` (`itemid`, `stattype`, `useraction`, `dtcreated`) VALUES
(2, 1, 1, '2012-03-20 11:08:25'),
(2, 1, 1, '2012-03-26 15:36:46'),
(2, 1, 1, '2012-03-26 15:38:14'),
(2, 1, 1, '2012-03-27 11:03:49'),
(2, 1, 1, '2012-03-27 11:09:55'),
(2, 1, 1, '2012-03-27 11:15:21'),
(2, 1, 1, '2012-03-27 11:15:50'),
(7, 1, 2, '2012-04-25 12:07:29'),
(7, 1, 2, '2012-04-25 12:08:14'),
(2, 1, 2, '2012-04-26 09:55:00'),
(2, 1, 2, '2012-04-26 09:55:04'),
(2, 1, 2, '2012-04-26 09:55:09'),
(2, 1, 2, '2012-04-26 09:55:14'),
(2, 1, 2, '2012-04-26 09:55:18'),
(2, 1, 2, '2012-04-26 09:55:26'),
(2, 1, 2, '2012-04-26 09:56:07'),
(2, 1, 2, '2012-04-26 09:56:14'),
(2, 1, 2, '2012-04-26 09:57:41'),
(2, 1, 2, '2012-04-26 09:58:02'),
(2, 1, 2, '2012-04-26 09:58:33'),
(7, 1, 2, '2012-04-26 11:59:54'),
(7, 1, 2, '2012-04-26 12:00:38'),
(7, 1, 2, '2012-04-26 12:30:44'),
(7, 2, 4, '2012-04-26 12:31:28'),
(3, 2, 4, '2012-04-26 12:35:59'),
(3, 2, 4, '2012-04-26 12:40:17'),
(1, 1, 2, '2012-04-28 12:14:34'),
(1, 1, 2, '2012-05-05 14:21:42'),
(1, 1, 2, '2012-05-05 14:22:14'),
(1, 1, 1, '2012-05-05 14:22:41'),
(1, 1, 1, '2012-05-05 14:24:56'),
(1, 1, 1, '2012-05-05 15:22:24'),
(1, 1, 1, '2012-05-05 15:22:47'),
(1, 1, 1, '2012-05-05 15:23:03'),
(1, 1, 1, '2012-05-05 15:23:45'),
(1, 1, 1, '2012-05-05 15:31:42'),
(1, 1, 1, '2012-05-05 15:32:26'),
(1, 1, 1, '2012-05-05 15:32:58'),
(1, 1, 2, '2012-05-07 09:21:08'),
(1, 1, 2, '2012-05-07 09:21:17'),
(1, 1, 2, '2012-05-07 09:21:57'),
(3, 1, 2, '2012-05-07 12:54:19'),
(1, 1, 1, '2012-05-07 13:04:29'),
(3, 1, 2, '2012-05-07 14:54:41'),
(1, 1, 2, '2012-05-07 18:32:59'),
(3, 1, 2, '2012-05-07 18:34:59'),
(2, 1, 2, '2012-05-07 18:36:59'),
(3, 1, 2, '2012-05-12 00:10:48'),
(2, 1, 2, '2012-05-12 00:13:28'),
(1, 1, 2, '2012-05-12 00:16:09'),
(1, 1, 2, '2012-05-12 02:20:41'),
(2, 1, 2, '2012-05-12 03:49:23'),
(3, 1, 2, '2012-05-12 06:48:15'),
(1, 1, 2, '2012-05-12 07:32:59'),
(1, 1, 2, '2012-05-12 16:51:12'),
(3, 1, 2, '2012-05-12 17:06:08'),
(1, 1, 2, '2012-05-12 18:07:47'),
(3, 1, 2, '2012-05-12 18:12:48'),
(2, 1, 2, '2012-05-12 18:17:49'),
(2, 1, 2, '2012-05-12 20:36:11'),
(3, 1, 2, '2012-05-14 03:28:24'),
(1, 1, 2, '2012-05-14 03:54:56'),
(2, 1, 2, '2012-05-14 22:44:53'),
(2, 1, 2, '2012-05-19 07:19:51'),
(1, 1, 2, '2012-05-19 07:19:52'),
(1, 1, 2, '2012-05-21 07:52:00'),
(3, 1, 2, '2012-05-21 10:29:52'),
(2, 1, 2, '2012-05-21 11:49:07'),
(1, 1, 2, '2012-05-23 02:52:05'),
(3, 1, 2, '2012-05-23 02:59:32'),
(2, 1, 2, '2012-05-23 07:04:37'),
(3, 1, 2, '2012-05-23 21:08:15'),
(3, 1, 2, '2012-05-24 09:35:59'),
(2, 1, 2, '2012-05-26 08:41:04'),
(1, 1, 2, '2012-05-26 08:41:07'),
(1, 1, 2, '2012-05-29 20:15:39'),
(2, 1, 2, '2012-05-29 20:15:42'),
(3, 1, 2, '2012-05-29 20:15:45'),
(1, 1, 2, '2012-06-01 17:06:07'),
(2, 1, 2, '2012-06-02 09:44:42'),
(3, 1, 2, '2012-06-02 12:06:20'),
(2, 1, 2, '2012-06-03 03:07:47'),
(3, 1, 2, '2012-06-03 14:03:46'),
(1, 1, 2, '2012-06-03 14:15:36'),
(2, 1, 2, '2012-06-05 00:35:00'),
(3, 1, 2, '2012-06-05 05:35:18'),
(1, 1, 2, '2012-06-06 19:19:59'),
(2, 1, 2, '2012-06-07 15:02:20'),
(3, 1, 2, '2012-06-07 17:01:36'),
(2, 1, 2, '2012-06-08 15:57:11'),
(3, 1, 2, '2012-06-09 12:12:39'),
(2, 1, 2, '2012-06-09 18:38:57'),
(2, 1, 2, '2012-06-09 18:38:57'),
(1, 1, 2, '2012-06-09 18:38:58'),
(1, 1, 2, '2012-06-09 18:38:58'),
(1, 1, 2, '2012-06-13 04:14:10'),
(3, 1, 2, '2012-06-13 05:36:18'),
(1, 1, 2, '2012-06-13 22:05:08'),
(2, 1, 2, '2012-06-14 16:54:27'),
(3, 1, 2, '2012-06-14 18:36:21'),
(2, 1, 2, '2012-06-16 16:58:14'),
(1, 1, 2, '2012-06-16 16:58:15'),
(2, 1, 2, '2012-06-24 14:26:04'),
(1, 1, 2, '2012-06-24 14:26:05'),
(1, 1, 2, '2012-06-25 19:19:00'),
(2, 1, 2, '2012-06-26 18:36:16'),
(2, 1, 2, '2012-06-28 16:21:17'),
(3, 1, 2, '2012-06-29 07:54:53'),
(3, 1, 2, '2012-06-29 12:39:52'),
(2, 1, 2, '2012-06-30 23:49:20'),
(1, 1, 2, '2012-06-30 23:49:21'),
(3, 1, 2, '2012-07-01 01:10:53'),
(2, 1, 2, '2012-07-01 01:50:16'),
(1, 1, 2, '2012-07-01 02:03:21'),
(2, 1, 2, '2012-07-01 02:07:05'),
(1, 1, 2, '2012-07-01 20:31:59'),
(3, 1, 2, '2012-07-02 06:07:37'),
(1, 1, 2, '2012-07-05 11:29:23'),
(2, 1, 2, '2012-07-07 20:07:57'),
(1, 1, 2, '2012-07-07 20:07:58'),
(2, 1, 2, '2012-07-09 18:40:25'),
(3, 1, 2, '2012-07-10 18:40:06'),
(3, 1, 2, '2012-07-11 11:25:42'),
(1, 1, 2, '2012-07-11 16:14:12'),
(2, 1, 2, '2012-07-12 17:53:31'),
(1, 1, 2, '2012-07-13 07:40:57'),
(3, 1, 2, '2012-07-13 08:34:08'),
(1, 1, 2, '2012-07-13 23:54:19'),
(1, 1, 2, '2012-07-14 04:37:11'),
(2, 1, 2, '2012-07-14 07:03:16'),
(2, 1, 2, '2012-07-14 18:10:38'),
(1, 1, 2, '2012-07-14 18:10:39'),
(1, 1, 2, '2012-07-15 09:16:09'),
(1, 1, 2, '2012-07-19 15:21:07'),
(2, 1, 2, '2012-07-19 15:21:07'),
(3, 1, 2, '2012-07-19 15:21:07'),
(1, 1, 2, '2012-07-19 15:21:08'),
(2, 1, 2, '2012-07-19 15:21:09'),
(3, 1, 2, '2012-07-19 15:21:10'),
(2, 1, 2, '2012-07-21 18:42:51'),
(1, 1, 2, '2012-07-21 18:42:52'),
(2, 1, 2, '2012-07-25 03:06:53'),
(1, 1, 2, '2012-07-28 09:32:00'),
(2, 1, 2, '2012-07-28 12:31:54'),
(2, 1, 2, '2012-07-28 14:29:46'),
(1, 1, 2, '2012-07-28 14:29:47'),
(1, 1, 2, '2012-07-28 17:46:49'),
(2, 1, 2, '2012-07-28 19:16:48'),
(2, 1, 2, '2012-07-28 22:29:08'),
(2, 1, 2, '2012-07-31 03:02:43'),
(1, 1, 2, '2012-07-31 03:05:55'),
(3, 1, 2, '2012-07-31 03:08:18'),
(1, 1, 2, '2012-07-31 03:08:56'),
(3, 1, 2, '2012-07-31 04:07:37'),
(1, 1, 2, '2012-07-31 13:55:30'),
(1, 1, 2, '2012-08-01 18:11:11'),
(3, 1, 2, '2012-08-03 03:23:46'),
(2, 1, 2, '2012-08-03 15:26:10'),
(2, 1, 2, '2012-08-04 20:55:22'),
(1, 1, 2, '2012-08-05 03:33:15'),
(3, 1, 2, '2012-08-05 06:54:47'),
(1, 1, 2, '2012-08-06 12:52:46'),
(2, 1, 2, '2012-08-11 11:44:26'),
(1, 1, 2, '2012-08-11 11:44:27'),
(1, 1, 2, '2012-08-12 09:17:02'),
(3, 1, 2, '2012-08-12 10:35:28'),
(2, 1, 2, '2012-08-13 10:12:09'),
(2, 1, 2, '2012-08-16 02:26:45'),
(1, 1, 2, '2012-08-16 02:48:44'),
(2, 1, 2, '2012-08-18 16:00:41'),
(1, 1, 2, '2012-08-18 16:00:43'),
(1, 1, 2, '2012-08-21 08:23:09'),
(1, 1, 2, '2012-08-23 01:25:33'),
(2, 1, 2, '2012-08-23 05:55:34'),
(2, 1, 2, '2012-08-23 13:42:43'),
(2, 1, 2, '2012-08-25 01:02:46'),
(2, 1, 2, '2012-08-25 19:44:44'),
(1, 1, 2, '2012-08-25 19:44:46'),
(1, 1, 2, '2012-08-28 15:21:11'),
(3, 1, 2, '2012-08-28 17:39:40'),
(2, 1, 2, '2012-08-28 19:04:23'),
(2, 1, 2, '2012-08-30 08:30:53'),
(3, 1, 2, '2012-08-31 05:17:03'),
(2, 1, 2, '2012-08-31 05:19:04'),
(2, 1, 2, '2012-09-01 14:51:20'),
(1, 1, 2, '2012-09-01 14:51:22'),
(3, 1, 2, '2012-09-07 05:43:35'),
(1, 1, 2, '2012-09-07 20:31:23'),
(2, 1, 2, '2012-09-07 20:31:23'),
(3, 1, 2, '2012-09-07 20:31:24'),
(3, 1, 2, '2012-09-08 08:51:27'),
(1, 1, 2, '2012-09-08 18:38:30'),
(2, 1, 2, '2012-09-08 19:16:04'),
(1, 1, 2, '2012-09-10 21:37:55'),
(2, 1, 2, '2012-09-13 19:12:18'),
(1, 1, 2, '2012-09-14 22:45:16'),
(2, 1, 2, '2012-09-21 22:39:41'),
(1, 1, 2, '2012-09-21 22:39:42'),
(2, 1, 2, '2012-09-22 16:09:03'),
(1, 1, 2, '2012-09-22 16:09:04'),
(3, 1, 2, '2012-09-22 21:39:12'),
(2, 1, 2, '2012-09-23 06:55:53'),
(1, 1, 2, '2012-09-23 06:55:54'),
(1, 1, 2, '2012-09-26 09:49:13'),
(2, 1, 2, '2012-09-28 02:02:53'),
(1, 1, 2, '2012-09-28 04:17:53'),
(2, 1, 2, '2012-09-28 06:32:53'),
(2, 1, 2, '2012-09-30 02:23:49'),
(1, 1, 2, '2012-09-30 02:23:51'),
(1, 1, 2, '2012-10-04 12:23:34'),
(2, 1, 2, '2012-10-04 13:37:16'),
(2, 1, 2, '2012-10-06 14:51:12'),
(1, 1, 2, '2012-10-06 14:51:14'),
(2, 1, 2, '2012-10-06 21:56:29'),
(3, 1, 2, '2012-10-08 17:49:37'),
(1, 1, 2, '2012-10-12 01:52:12'),
(2, 1, 2, '2012-10-13 15:16:00'),
(1, 1, 2, '2012-10-13 15:16:02'),
(1, 1, 2, '2012-10-15 09:41:09'),
(2, 1, 2, '2012-10-15 09:41:11'),
(3, 1, 2, '2012-10-15 09:41:13'),
(1, 1, 2, '2012-10-15 09:41:16'),
(2, 1, 2, '2012-10-15 09:41:21'),
(3, 1, 2, '2012-10-15 09:41:27'),
(3, 1, 2, '2012-10-17 17:01:58'),
(1, 1, 2, '2012-10-18 01:54:00'),
(3, 1, 2, '2012-10-19 10:04:00'),
(1, 1, 2, '2012-10-20 00:39:38'),
(2, 1, 2, '2012-10-20 14:49:32'),
(1, 1, 2, '2012-10-20 14:49:34'),
(2, 1, 2, '2012-10-22 01:41:41'),
(1, 1, 2, '2012-10-22 21:03:53'),
(2, 1, 2, '2012-10-23 05:30:34'),
(3, 1, 2, '2012-10-24 02:02:17'),
(1, 1, 2, '2012-10-25 08:36:25'),
(2, 1, 2, '2012-10-26 23:33:06'),
(1, 1, 2, '2012-10-26 23:33:10'),
(1, 1, 2, '2012-10-27 09:54:26'),
(1, 1, 2, '2012-10-28 04:39:46'),
(2, 1, 2, '2012-10-28 04:41:32'),
(3, 1, 2, '2012-10-28 04:43:29'),
(1, 1, 2, '2012-10-28 04:45:21'),
(2, 1, 2, '2012-10-28 04:47:15'),
(3, 1, 2, '2012-10-28 04:49:05'),
(1, 1, 2, '2012-10-30 14:32:23'),
(2, 1, 2, '2012-10-30 14:32:25'),
(3, 1, 2, '2012-10-30 14:32:26'),
(1, 1, 2, '2012-10-30 14:32:28'),
(2, 1, 2, '2012-10-30 14:32:30'),
(3, 1, 2, '2012-10-30 14:32:32'),
(2, 1, 2, '2012-11-01 16:53:43'),
(1, 1, 2, '2012-11-01 16:53:46'),
(1, 1, 2, '2012-11-02 05:26:23'),
(2, 1, 2, '2012-11-02 05:45:09'),
(3, 1, 2, '2012-11-02 06:03:53'),
(1, 1, 2, '2012-11-02 06:23:05'),
(2, 1, 2, '2012-11-02 06:41:25'),
(3, 1, 2, '2012-11-02 07:00:08'),
(1, 1, 2, '2012-11-03 21:38:38'),
(2, 1, 2, '2012-11-03 21:38:40'),
(3, 1, 2, '2012-11-03 21:38:41'),
(1, 1, 2, '2012-11-03 21:38:43'),
(2, 1, 2, '2012-11-03 21:38:45'),
(3, 1, 2, '2012-11-03 21:38:47'),
(1, 1, 2, '2012-11-03 22:55:44'),
(2, 1, 2, '2012-11-04 10:48:09'),
(3, 1, 2, '2012-11-04 11:39:17'),
(1, 1, 2, '2012-11-08 11:15:37'),
(1, 1, 2, '2012-11-08 11:28:39'),
(2, 1, 2, '2012-11-08 11:28:47'),
(3, 1, 2, '2012-11-08 11:28:53'),
(1, 1, 2, '2012-11-08 11:29:04'),
(2, 1, 2, '2012-11-08 11:29:21'),
(3, 1, 2, '2012-11-08 11:29:37'),
(3, 1, 2, '2012-11-08 12:57:50'),
(2, 1, 2, '2012-11-08 13:19:10'),
(2, 1, 2, '2012-11-08 13:29:30'),
(3, 1, 2, '2012-11-09 22:33:02'),
(2, 1, 2, '2012-11-10 05:52:36'),
(1, 1, 2, '2012-11-11 20:46:09'),
(1, 1, 2, '2012-11-13 13:15:40'),
(2, 1, 2, '2012-11-13 13:47:39'),
(3, 1, 2, '2012-11-14 11:53:16'),
(1, 1, 2, '2012-11-14 11:53:19'),
(2, 1, 2, '2012-11-14 16:29:36'),
(3, 1, 2, '2012-11-15 03:43:42'),
(2, 1, 2, '2012-11-16 01:36:12'),
(2, 1, 2, '2012-11-16 01:36:30'),
(3, 1, 2, '2012-11-16 01:36:33'),
(3, 1, 2, '2012-11-16 01:36:36'),
(3, 1, 2, '2012-11-17 23:33:26'),
(1, 1, 2, '2012-11-17 23:47:13'),
(1, 1, 2, '2012-11-18 05:26:37'),
(2, 1, 2, '2012-11-18 09:57:35'),
(2, 1, 2, '2012-11-22 12:38:22'),
(2, 1, 2, '2012-11-22 12:38:45'),
(3, 1, 2, '2012-11-22 12:38:48'),
(3, 1, 2, '2012-11-22 12:38:50'),
(3, 1, 2, '2012-11-23 17:01:58'),
(1, 1, 2, '2012-11-25 04:28:25'),
(2, 1, 2, '2012-11-25 04:28:27'),
(3, 1, 2, '2012-11-25 04:28:29'),
(1, 1, 2, '2012-11-25 04:28:32'),
(2, 1, 2, '2012-11-25 04:28:35'),
(3, 1, 2, '2012-11-25 04:28:40'),
(1, 1, 2, '2012-11-27 01:01:34'),
(1, 1, 2, '2012-11-28 03:50:33'),
(2, 1, 2, '2012-11-28 04:35:35'),
(3, 1, 2, '2012-11-28 05:20:38'),
(1, 1, 2, '2012-11-28 06:05:36'),
(2, 1, 2, '2012-11-28 06:50:35'),
(3, 1, 2, '2012-11-28 07:35:41'),
(2, 1, 2, '2012-11-29 12:40:59'),
(2, 1, 2, '2012-11-29 12:41:16'),
(3, 1, 2, '2012-11-29 12:41:20'),
(3, 1, 2, '2012-11-29 12:41:23'),
(3, 1, 2, '2012-12-04 08:02:55'),
(2, 1, 2, '2012-12-06 13:43:32'),
(3, 1, 2, '2012-12-06 13:43:39'),
(3, 1, 2, '2012-12-06 13:43:45'),
(1, 1, 2, '2012-12-06 13:43:54'),
(2, 1, 2, '2012-12-06 13:43:55'),
(1, 1, 2, '2012-12-06 13:44:03'),
(2, 1, 2, '2012-12-07 09:03:02'),
(2, 1, 2, '2012-12-08 11:36:53'),
(3, 1, 2, '2012-12-09 05:03:13'),
(2, 1, 2, '2012-12-11 19:39:57'),
(1, 1, 2, '2012-12-12 12:54:55'),
(2, 1, 2, '2012-12-13 14:10:28'),
(3, 1, 2, '2012-12-13 14:10:35'),
(3, 1, 2, '2012-12-13 14:10:41'),
(1, 1, 2, '2012-12-13 14:10:51'),
(2, 1, 2, '2012-12-13 14:10:52'),
(1, 1, 2, '2012-12-13 14:11:00'),
(1, 1, 2, '2012-12-13 14:40:23'),
(3, 1, 2, '2012-12-13 21:11:44'),
(2, 1, 2, '2012-12-14 00:06:03'),
(2, 1, 2, '2012-12-15 04:01:02'),
(1, 1, 2, '2012-12-15 06:18:30'),
(2, 1, 2, '2012-12-15 07:39:06'),
(1, 1, 2, '2012-12-15 10:10:00'),
(3, 1, 2, '2012-12-15 12:37:24'),
(3, 1, 2, '2012-12-15 16:39:29'),
(1, 1, 2, '2012-12-15 17:57:11'),
(3, 1, 2, '2012-12-16 08:34:35'),
(1, 1, 2, '2012-12-17 13:35:31'),
(1, 1, 2, '2012-12-19 19:52:20'),
(2, 1, 2, '2012-12-20 13:21:32'),
(3, 1, 2, '2012-12-20 13:21:38'),
(3, 1, 2, '2012-12-20 13:21:45'),
(1, 1, 2, '2012-12-20 13:21:58'),
(2, 1, 2, '2012-12-20 13:21:59'),
(1, 1, 2, '2012-12-20 13:22:06'),
(1, 1, 2, '2012-12-21 05:25:26'),
(2, 1, 2, '2012-12-21 17:46:04'),
(3, 1, 2, '2012-12-21 17:58:22'),
(1, 1, 2, '2012-12-22 00:12:40'),
(2, 1, 2, '2012-12-22 01:02:45'),
(3, 1, 2, '2012-12-22 04:12:42'),
(2, 1, 2, '2012-12-22 12:41:21'),
(3, 1, 2, '2012-12-24 08:38:37'),
(3, 1, 1, '2012-12-24 17:25:12'),
(3, 1, 2, '2012-12-25 10:55:47'),
(3, 1, 2, '2012-12-25 18:30:45'),
(2, 1, 2, '2012-12-25 19:05:43'),
(1, 1, 2, '2012-12-26 00:39:47'),
(2, 1, 2, '2012-12-26 04:15:56'),
(1, 1, 2, '2012-12-26 06:53:48'),
(1, 1, 2, '2012-12-27 20:36:45'),
(2, 1, 2, '2012-12-31 09:34:14'),
(3, 1, 2, '2012-12-31 15:52:26'),
(3, 1, 2, '2012-12-31 16:34:53'),
(1, 1, 2, '2013-01-01 09:31:14'),
(2, 1, 2, '2013-01-01 11:14:30'),
(1, 1, 2, '2013-01-01 11:38:04'),
(3, 1, 2, '2013-01-03 16:29:39'),
(1, 1, 2, '2013-01-03 16:47:53'),
(2, 1, 2, '2013-01-03 17:18:03'),
(1, 1, 2, '2013-01-05 08:25:27'),
(2, 1, 2, '2013-01-05 16:37:09'),
(3, 1, 2, '2013-01-05 22:22:57'),
(2, 1, 2, '2013-01-06 13:18:10'),
(3, 1, 2, '2013-01-06 13:18:19'),
(3, 1, 2, '2013-01-06 13:18:29'),
(1, 1, 2, '2013-01-06 13:18:42'),
(2, 1, 2, '2013-01-06 13:18:44'),
(1, 1, 2, '2013-01-06 13:18:52'),
(2, 1, 2, '2013-01-06 22:08:25'),
(3, 1, 2, '2013-01-06 22:18:24'),
(2, 1, 2, '2013-01-07 00:37:31'),
(1, 1, 2, '2013-01-07 01:24:42'),
(2, 1, 2, '2013-01-07 18:01:29'),
(3, 1, 2, '2013-01-07 18:01:36'),
(3, 1, 2, '2013-01-07 18:01:43'),
(1, 1, 2, '2013-01-07 18:01:53'),
(2, 1, 2, '2013-01-07 18:01:55'),
(1, 1, 2, '2013-01-07 18:02:04'),
(1, 1, 2, '2013-01-08 09:09:14'),
(2, 1, 2, '2013-01-08 09:09:15'),
(3, 1, 2, '2013-01-08 09:09:15'),
(1, 1, 2, '2013-01-08 09:09:16'),
(2, 1, 2, '2013-01-08 09:09:18'),
(3, 1, 2, '2013-01-08 09:09:19'),
(1, 1, 2, '2013-01-08 16:18:02'),
(2, 1, 2, '2013-01-08 16:18:03'),
(3, 1, 2, '2013-01-08 16:18:04'),
(1, 1, 2, '2013-01-08 16:18:07'),
(2, 1, 2, '2013-01-08 16:18:10'),
(3, 1, 2, '2013-01-08 16:18:13'),
(3, 1, 2, '2013-01-08 20:35:11'),
(2, 1, 2, '2013-01-11 00:46:37'),
(3, 1, 2, '2013-01-11 00:46:42'),
(3, 1, 2, '2013-01-11 00:46:50'),
(1, 1, 2, '2013-01-11 00:46:59'),
(2, 1, 2, '2013-01-11 00:47:00'),
(1, 1, 2, '2013-01-11 00:47:10'),
(1, 1, 2, '2013-01-12 04:26:16'),
(3, 1, 2, '2013-01-12 13:29:07'),
(2, 1, 2, '2013-01-12 16:22:03'),
(1, 1, 2, '2013-01-12 19:53:46'),
(2, 1, 2, '2013-01-12 19:53:54'),
(3, 1, 2, '2013-01-12 19:54:01'),
(1, 1, 2, '2013-01-12 19:54:16'),
(2, 1, 2, '2013-01-12 19:54:42'),
(3, 1, 2, '2013-01-12 19:55:03'),
(2, 1, 2, '2013-01-12 20:16:26'),
(3, 1, 2, '2013-01-12 20:16:55'),
(3, 1, 2, '2013-01-12 20:17:16'),
(2, 1, 2, '2013-01-12 20:17:32'),
(1, 1, 2, '2013-01-12 20:17:36'),
(2, 1, 2, '2013-01-13 05:29:30'),
(3, 1, 2, '2013-01-13 17:40:40'),
(2, 1, 2, '2013-01-13 18:19:56'),
(2, 1, 2, '2013-01-13 22:00:34'),
(1, 1, 2, '2013-01-14 01:06:12'),
(3, 1, 2, '2013-01-14 04:15:15'),
(2, 1, 2, '2013-01-14 09:47:31'),
(1, 1, 2, '2013-01-14 10:49:13'),
(3, 1, 2, '2013-01-14 19:47:14'),
(1, 1, 2, '2013-01-16 21:46:17'),
(3, 1, 2, '2013-01-16 22:09:27'),
(1, 1, 2, '2013-01-17 11:28:19'),
(1, 1, 2, '2013-01-17 18:44:05'),
(3, 1, 2, '2013-01-17 19:22:36'),
(2, 1, 2, '2013-01-17 19:58:59'),
(1, 1, 2, '2013-01-18 04:04:33'),
(2, 1, 2, '2013-01-18 16:04:26'),
(3, 1, 2, '2013-01-18 16:04:33'),
(3, 1, 2, '2013-01-18 16:04:39'),
(1, 1, 2, '2013-01-18 16:04:49'),
(2, 1, 2, '2013-01-18 16:04:51'),
(1, 1, 2, '2013-01-18 16:04:59'),
(3, 1, 2, '2013-01-18 18:59:46'),
(1, 1, 2, '2013-01-19 02:10:52'),
(2, 1, 2, '2013-01-19 02:10:57'),
(3, 1, 2, '2013-01-19 02:10:59'),
(1, 1, 2, '2013-01-19 02:11:15'),
(2, 1, 2, '2013-01-19 02:11:24'),
(3, 1, 2, '2013-01-19 02:11:39'),
(1, 1, 2, '2013-01-19 02:12:05'),
(2, 1, 2, '2013-01-19 02:12:07'),
(3, 1, 2, '2013-01-19 02:12:12'),
(1, 1, 2, '2013-01-19 02:12:19'),
(2, 1, 2, '2013-01-19 02:12:27'),
(3, 1, 2, '2013-01-19 02:12:37'),
(1, 1, 2, '2013-01-19 02:14:12'),
(2, 1, 2, '2013-01-19 02:14:24'),
(3, 1, 2, '2013-01-19 02:14:26'),
(1, 1, 2, '2013-01-19 02:14:50'),
(2, 1, 2, '2013-01-19 02:15:08'),
(3, 1, 2, '2013-01-19 02:15:20'),
(2, 1, 2, '2013-01-22 04:32:34'),
(2, 1, 2, '2013-01-22 17:17:19'),
(3, 1, 2, '2013-01-24 00:42:56'),
(3, 1, 2, '2013-01-24 02:24:29'),
(1, 1, 2, '2013-01-24 02:24:30'),
(2, 1, 2, '2013-01-24 02:24:30'),
(1, 1, 2, '2013-01-24 02:24:30'),
(2, 1, 2, '2013-01-24 02:24:31'),
(3, 1, 2, '2013-01-24 02:24:32'),
(2, 1, 2, '2013-01-24 20:36:02'),
(3, 1, 2, '2013-01-24 20:36:14'),
(3, 1, 2, '2013-01-24 20:36:23'),
(1, 1, 2, '2013-01-24 20:36:36'),
(2, 1, 2, '2013-01-24 20:36:38'),
(1, 1, 2, '2013-01-24 20:36:47'),
(1, 1, 2, '2013-01-26 01:39:45'),
(2, 1, 2, '2013-01-26 01:39:46'),
(3, 1, 2, '2013-01-26 01:39:47'),
(1, 1, 2, '2013-01-26 01:39:47'),
(2, 1, 2, '2013-01-26 01:39:49'),
(3, 1, 2, '2013-01-26 01:39:50'),
(1, 1, 2, '2013-01-27 08:39:17'),
(1, 1, 2, '2013-01-30 15:47:42'),
(1, 1, 2, '2013-01-31 16:04:57'),
(1, 1, 2, '2013-01-31 19:40:49'),
(2, 1, 2, '2013-01-31 19:52:56'),
(3, 1, 2, '2013-01-31 19:56:19'),
(3, 1, 2, '2013-01-31 20:11:49'),
(2, 1, 2, '2013-01-31 20:13:18'),
(1, 1, 2, '2013-01-31 20:13:23'),
(2, 1, 2, '2013-02-01 15:31:05'),
(3, 1, 2, '2013-02-01 15:31:14'),
(3, 1, 2, '2013-02-01 15:31:21'),
(1, 1, 2, '2013-02-01 15:31:31'),
(2, 1, 2, '2013-02-01 15:31:32'),
(1, 1, 2, '2013-02-01 15:31:40'),
(3, 1, 2, '2013-02-02 15:09:12'),
(3, 1, 2, '2013-02-02 18:42:14'),
(1, 1, 2, '2013-02-02 18:43:11'),
(2, 1, 2, '2013-02-02 18:44:12'),
(1, 1, 2, '2013-02-02 18:47:50'),
(2, 1, 2, '2013-02-02 19:02:21'),
(3, 1, 2, '2013-02-02 19:08:18'),
(3, 1, 2, '2013-02-02 21:16:55'),
(1, 1, 2, '2013-02-02 21:36:05'),
(2, 1, 2, '2013-02-03 02:49:02'),
(2, 1, 2, '2013-02-03 02:56:07'),
(3, 1, 2, '2013-02-06 07:05:23'),
(2, 1, 2, '2013-02-06 07:05:24'),
(1, 1, 2, '2013-02-06 07:05:24'),
(2, 1, 2, '2013-02-06 08:31:26'),
(2, 1, 2, '2013-02-07 17:09:38'),
(3, 1, 2, '2013-02-07 17:09:46'),
(3, 1, 2, '2013-02-07 17:09:53'),
(1, 1, 2, '2013-02-07 17:10:06'),
(2, 1, 2, '2013-02-07 17:10:08'),
(1, 1, 2, '2013-02-07 17:10:18'),
(3, 1, 2, '2013-02-08 04:39:19'),
(3, 1, 2, '2013-02-08 12:17:05'),
(3, 1, 2, '2013-02-08 12:18:20'),
(1, 1, 2, '2013-02-08 12:18:53'),
(2, 1, 2, '2013-02-08 12:19:05'),
(2, 1, 2, '2013-02-08 12:20:36'),
(1, 1, 2, '2013-02-08 12:21:22'),
(2, 1, 2, '2013-02-08 17:50:45'),
(1, 1, 2, '2013-02-08 21:08:51'),
(3, 1, 2, '2013-02-09 23:32:24'),
(1, 1, 2, '2013-02-11 16:27:49'),
(1, 1, 2, '2013-02-14 12:53:38'),
(2, 1, 2, '2013-02-14 12:53:41'),
(3, 1, 2, '2013-02-14 12:53:45'),
(1, 1, 2, '2013-02-14 12:53:48'),
(2, 1, 2, '2013-02-14 12:53:51'),
(3, 1, 2, '2013-02-14 12:53:53'),
(1, 1, 2, '2013-02-14 13:18:58'),
(2, 1, 2, '2013-02-14 13:19:01'),
(3, 1, 2, '2013-02-14 13:19:04'),
(1, 1, 2, '2013-02-14 13:19:08'),
(2, 1, 2, '2013-02-14 13:19:11'),
(3, 1, 2, '2013-02-14 13:19:15'),
(1, 1, 2, '2013-02-14 18:53:24'),
(2, 1, 2, '2013-02-14 18:53:27'),
(3, 1, 2, '2013-02-14 18:53:33'),
(1, 1, 2, '2013-02-14 18:53:39'),
(2, 1, 2, '2013-02-14 18:53:42'),
(3, 1, 2, '2013-02-14 18:53:45'),
(2, 1, 2, '2013-02-14 19:16:20'),
(3, 1, 2, '2013-02-14 19:16:26'),
(3, 1, 2, '2013-02-14 19:16:34'),
(1, 1, 2, '2013-02-14 19:16:44'),
(2, 1, 2, '2013-02-14 19:16:46'),
(1, 1, 2, '2013-02-14 19:16:53'),
(3, 1, 2, '2013-02-15 15:42:02'),
(1, 1, 2, '2013-02-15 17:32:53'),
(2, 1, 2, '2013-02-15 17:33:00'),
(3, 1, 2, '2013-02-15 17:33:07'),
(1, 1, 2, '2013-02-15 17:33:11'),
(2, 1, 2, '2013-02-15 17:33:20'),
(1, 1, 2, '2013-02-16 13:09:06'),
(1, 1, 2, '2013-02-17 15:02:33'),
(2, 1, 2, '2013-02-17 15:02:36'),
(3, 1, 2, '2013-02-17 15:02:40'),
(1, 1, 2, '2013-02-17 15:02:46'),
(2, 1, 2, '2013-02-17 15:02:54'),
(3, 1, 2, '2013-02-17 15:03:03'),
(2, 1, 2, '2013-02-18 21:29:46'),
(1, 1, 2, '2013-02-19 03:38:43'),
(2, 1, 2, '2013-02-19 03:38:46'),
(3, 1, 2, '2013-02-19 03:38:49'),
(1, 1, 2, '2013-02-19 03:38:51'),
(2, 1, 2, '2013-02-19 03:38:53'),
(3, 1, 2, '2013-02-19 03:38:55'),
(1, 1, 2, '2013-02-19 12:43:52'),
(2, 1, 2, '2013-02-19 12:43:55'),
(3, 1, 2, '2013-02-19 12:43:57'),
(1, 1, 2, '2013-02-19 12:43:59'),
(2, 1, 2, '2013-02-19 12:44:01'),
(3, 1, 2, '2013-02-19 12:44:03'),
(1, 1, 2, '2013-02-19 13:55:47'),
(2, 1, 2, '2013-02-19 13:55:50'),
(3, 1, 2, '2013-02-19 13:55:53'),
(1, 1, 2, '2013-02-19 13:55:58'),
(2, 1, 2, '2013-02-19 13:56:01'),
(3, 1, 2, '2013-02-19 13:56:03'),
(1, 1, 2, '2013-02-20 23:59:51'),
(2, 1, 2, '2013-02-20 23:59:52'),
(3, 1, 2, '2013-02-20 23:59:54'),
(1, 1, 2, '2013-02-20 23:59:56'),
(2, 1, 2, '2013-02-20 23:59:58'),
(3, 1, 2, '2013-02-21 00:00:00'),
(2, 1, 2, '2013-02-21 14:55:45'),
(3, 1, 2, '2013-02-21 14:55:52'),
(3, 1, 2, '2013-02-21 14:55:57'),
(1, 1, 2, '2013-02-21 14:56:05'),
(2, 1, 2, '2013-02-21 14:56:07'),
(1, 1, 2, '2013-02-21 14:56:14'),
(2, 1, 2, '2013-02-22 00:36:54'),
(3, 1, 2, '2013-02-23 20:33:56'),
(3, 1, 2, '2013-02-25 10:55:27'),
(2, 1, 2, '2013-02-25 15:03:18'),
(1, 1, 2, '2013-02-26 05:57:34'),
(1, 1, 2, '2013-02-27 04:36:52'),
(2, 1, 2, '2013-02-28 10:43:13'),
(3, 1, 2, '2013-02-28 11:43:36'),
(2, 1, 2, '2013-02-28 14:13:11'),
(3, 1, 2, '2013-02-28 14:13:17'),
(3, 1, 2, '2013-02-28 14:13:23'),
(1, 1, 2, '2013-02-28 14:13:32'),
(2, 1, 2, '2013-02-28 14:13:34'),
(1, 1, 2, '2013-02-28 14:13:41'),
(1, 1, 2, '2013-03-01 01:45:17'),
(2, 1, 2, '2013-03-04 03:20:13'),
(3, 1, 2, '2013-03-05 21:35:32'),
(1, 1, 2, '2013-03-06 05:53:35'),
(1, 1, 2, '2013-03-07 04:20:04'),
(2, 1, 2, '2013-03-07 04:20:07'),
(3, 1, 2, '2013-03-07 04:20:09'),
(1, 1, 2, '2013-03-07 04:20:10'),
(2, 1, 2, '2013-03-07 04:20:12'),
(3, 1, 2, '2013-03-07 04:20:14'),
(2, 1, 2, '2013-03-07 13:57:46'),
(3, 1, 2, '2013-03-07 13:57:52'),
(3, 1, 2, '2013-03-07 13:57:56'),
(1, 1, 2, '2013-03-07 13:58:06'),
(2, 1, 2, '2013-03-07 13:58:07'),
(1, 1, 2, '2013-03-07 13:58:16'),
(2, 1, 2, '2013-03-08 03:41:15'),
(2, 1, 2, '2013-03-09 04:34:11'),
(1, 1, 2, '2013-03-09 10:43:36'),
(2, 1, 2, '2013-03-09 10:43:37'),
(3, 1, 2, '2013-03-09 10:43:38'),
(3, 1, 2, '2013-03-09 14:33:12'),
(3, 1, 2, '2013-03-11 00:35:18'),
(3, 1, 2, '2013-03-13 16:55:52'),
(1, 1, 2, '2013-03-14 08:34:51'),
(2, 1, 2, '2013-03-14 12:35:48'),
(3, 1, 2, '2013-03-14 12:35:54'),
(3, 1, 2, '2013-03-14 12:35:58'),
(1, 1, 2, '2013-03-14 12:36:07'),
(2, 1, 2, '2013-03-14 12:36:08'),
(1, 1, 2, '2013-03-14 12:36:17'),
(1, 1, 2, '2013-03-16 10:13:52'),
(2, 1, 2, '2013-03-17 07:01:41'),
(1, 1, 2, '2013-03-17 20:46:07'),
(1, 1, 2, '2013-03-18 17:45:02'),
(1, 1, 2, '2013-03-19 10:43:41'),
(2, 1, 2, '2013-03-20 02:04:11'),
(2, 1, 2, '2013-03-21 13:38:40'),
(3, 1, 2, '2013-03-21 13:38:45'),
(3, 1, 2, '2013-03-21 13:38:50'),
(1, 1, 2, '2013-03-21 13:38:59'),
(2, 1, 2, '2013-03-21 13:39:00'),
(1, 1, 2, '2013-03-21 13:39:06'),
(1, 1, 2, '2013-03-23 05:25:25'),
(2, 1, 2, '2013-03-24 06:52:25'),
(2, 1, 2, '2013-03-24 08:34:18'),
(3, 1, 2, '2013-03-24 18:07:01'),
(1, 1, 2, '2013-03-25 00:55:53'),
(2, 1, 2, '2013-03-25 00:55:54'),
(3, 1, 2, '2013-03-25 00:55:55'),
(1, 1, 2, '2013-03-25 01:32:35'),
(2, 1, 2, '2013-03-25 01:32:41'),
(3, 1, 2, '2013-03-25 01:32:46'),
(1, 1, 2, '2013-03-25 01:32:58'),
(2, 1, 2, '2013-03-25 01:33:17'),
(3, 1, 2, '2013-03-25 01:33:34'),
(2, 1, 2, '2013-03-25 03:38:08'),
(2, 1, 2, '2013-03-25 23:48:00'),
(3, 1, 2, '2013-03-26 04:36:19'),
(3, 1, 2, '2013-03-27 01:02:49'),
(1, 1, 2, '2013-03-28 00:55:31'),
(1, 1, 2, '2013-03-28 04:54:33'),
(2, 1, 2, '2013-03-28 04:56:35'),
(3, 1, 2, '2013-03-28 05:13:04'),
(2, 1, 2, '2013-03-28 13:46:50'),
(3, 1, 2, '2013-03-28 13:46:58'),
(3, 1, 2, '2013-03-28 13:47:05'),
(1, 1, 2, '2013-03-28 13:47:20'),
(2, 1, 2, '2013-03-28 13:47:22'),
(1, 1, 2, '2013-03-28 13:47:31'),
(2, 1, 2, '2013-03-28 23:31:56'),
(1, 1, 2, '2013-03-29 12:53:55'),
(1, 1, 2, '2013-03-29 21:35:27'),
(2, 1, 2, '2013-03-29 21:35:56'),
(3, 1, 2, '2013-03-29 22:21:55'),
(1, 1, 2, '2013-03-30 00:37:29'),
(2, 1, 2, '2013-03-30 00:50:00'),
(3, 1, 2, '2013-03-30 01:05:57'),
(3, 1, 2, '2013-03-30 02:31:46'),
(1, 1, 2, '2013-03-30 04:26:26'),
(2, 1, 2, '2013-03-30 04:26:56'),
(3, 1, 2, '2013-03-30 04:40:26'),
(1, 1, 2, '2013-03-30 04:56:26'),
(2, 1, 2, '2013-03-30 04:58:56'),
(3, 1, 2, '2013-03-30 05:06:25'),
(2, 1, 2, '2013-04-02 02:10:11'),
(2, 1, 2, '2013-04-03 08:48:44'),
(2, 1, 2, '2013-04-04 12:17:51'),
(3, 1, 2, '2013-04-04 12:17:57'),
(3, 1, 2, '2013-04-04 12:18:04'),
(1, 1, 2, '2013-04-04 12:18:16'),
(2, 1, 2, '2013-04-04 12:18:17'),
(1, 1, 2, '2013-04-04 12:18:24'),
(1, 1, 2, '2013-04-05 10:09:30'),
(2, 1, 2, '2013-04-05 10:09:30'),
(3, 1, 2, '2013-04-05 10:09:30'),
(1, 1, 2, '2013-04-05 10:09:32'),
(2, 1, 2, '2013-04-05 10:09:32'),
(3, 1, 2, '2013-04-05 10:09:33'),
(1, 1, 2, '2013-04-06 14:19:19'),
(1, 1, 2, '2013-04-07 07:31:42'),
(3, 1, 2, '2013-04-07 20:11:43'),
(1, 1, 2, '2013-04-07 22:18:21'),
(2, 1, 2, '2013-04-08 13:28:12'),
(1, 1, 2, '2013-04-08 14:07:37'),
(2, 1, 2, '2013-04-09 22:23:46'),
(3, 1, 2, '2013-04-10 09:23:12'),
(3, 1, 2, '2013-04-11 03:14:42'),
(2, 1, 2, '2013-04-12 01:46:29'),
(3, 1, 2, '2013-04-12 01:46:37'),
(3, 1, 2, '2013-04-12 01:46:43'),
(1, 1, 2, '2013-04-12 01:46:52'),
(2, 1, 2, '2013-04-12 01:46:55'),
(1, 1, 2, '2013-04-12 01:47:04'),
(2, 1, 2, '2013-04-12 03:35:36'),
(1, 1, 2, '2013-04-13 03:19:51'),
(1, 1, 2, '2013-04-13 17:22:50'),
(3, 1, 2, '2013-04-13 18:00:00'),
(2, 1, 2, '2013-04-14 03:09:01'),
(1, 1, 2, '2013-04-15 00:07:44'),
(2, 1, 2, '2013-04-15 00:07:47'),
(3, 1, 2, '2013-04-15 00:07:49'),
(1, 1, 2, '2013-04-15 00:07:51'),
(2, 1, 2, '2013-04-15 00:07:53'),
(3, 1, 2, '2013-04-15 00:07:55'),
(3, 1, 2, '2013-04-15 02:46:53'),
(3, 1, 2, '2013-04-15 03:42:45'),
(1, 1, 2, '2013-04-17 01:40:00'),
(2, 1, 2, '2013-04-17 01:40:01'),
(3, 1, 2, '2013-04-17 01:40:02'),
(1, 1, 2, '2013-04-17 01:40:06'),
(2, 1, 2, '2013-04-17 01:40:08'),
(3, 1, 2, '2013-04-17 01:40:11'),
(3, 1, 2, '2013-04-21 08:08:58'),
(2, 1, 2, '2013-04-21 21:12:59'),
(3, 1, 2, '2013-04-22 04:17:42'),
(2, 1, 2, '2013-04-23 02:36:32'),
(3, 1, 2, '2013-04-23 02:36:39'),
(3, 1, 2, '2013-04-23 02:36:45'),
(1, 1, 2, '2013-04-23 02:36:59'),
(2, 1, 2, '2013-04-23 02:37:02'),
(1, 1, 2, '2013-04-23 02:37:09'),
(2, 1, 2, '2013-04-23 17:22:36'),
(3, 1, 2, '2013-04-25 13:21:51'),
(2, 1, 2, '2013-04-27 01:05:55'),
(3, 1, 2, '2013-04-27 01:06:04'),
(3, 1, 2, '2013-04-27 01:06:10'),
(1, 1, 2, '2013-04-27 01:06:22'),
(2, 1, 2, '2013-04-27 01:06:24'),
(1, 1, 2, '2013-04-27 01:06:31'),
(1, 1, 2, '2013-04-27 12:07:05'),
(2, 1, 2, '2013-04-27 20:22:18'),
(3, 1, 2, '2013-04-28 11:31:00'),
(1, 1, 2, '2013-04-28 19:51:18'),
(1, 1, 2, '2013-04-28 21:18:10'),
(2, 1, 2, '2013-04-29 12:30:22'),
(2, 1, 2, '2013-04-29 18:12:46'),
(3, 1, 2, '2013-04-29 21:28:12'),
(1, 1, 2, '2013-04-30 22:19:36'),
(1, 1, 2, '2013-05-04 09:33:34'),
(3, 1, 2, '2013-05-04 09:34:03'),
(2, 1, 2, '2013-05-04 09:36:02'),
(1, 1, 2, '2013-05-04 21:51:48'),
(1, 1, 2, '2013-05-06 14:10:39'),
(2, 1, 2, '2013-05-06 14:10:41'),
(3, 1, 2, '2013-05-06 14:10:43'),
(1, 1, 2, '2013-05-06 14:10:46'),
(2, 1, 2, '2013-05-06 14:10:49'),
(3, 1, 2, '2013-05-06 14:10:52'),
(2, 1, 2, '2013-05-09 09:41:46'),
(1, 1, 2, '2013-05-09 18:19:25'),
(2, 1, 2, '2013-05-09 18:19:28'),
(3, 1, 2, '2013-05-09 18:19:31'),
(1, 1, 2, '2013-05-09 18:19:35'),
(2, 1, 2, '2013-05-09 18:19:38'),
(3, 1, 2, '2013-05-09 18:19:40'),
(3, 1, 2, '2013-05-11 05:35:59'),
(1, 1, 2, '2013-05-12 03:34:29'),
(1, 1, 2, '2013-05-12 11:51:38'),
(3, 1, 2, '2013-05-12 11:51:38'),
(2, 1, 2, '2013-05-12 11:51:38'),
(3, 1, 2, '2013-05-13 20:01:02'),
(1, 1, 2, '2013-05-14 13:33:28'),
(3, 1, 2, '2013-05-15 14:54:47'),
(2, 1, 2, '2013-05-16 04:50:45'),
(1, 1, 2, '2013-05-17 06:51:29'),
(3, 1, 2, '2013-05-18 06:50:36'),
(2, 1, 2, '2013-05-18 23:32:24'),
(2, 1, 2, '2013-05-19 22:21:35'),
(1, 1, 2, '2013-05-22 19:19:55'),
(2, 1, 2, '2013-05-24 13:38:30'),
(3, 1, 2, '2013-05-26 09:39:11'),
(1, 1, 2, '2013-05-28 23:18:52'),
(1, 1, 2, '2013-05-29 17:40:20'),
(2, 1, 2, '2013-05-30 08:45:09'),
(1, 1, 2, '2013-05-30 19:37:03'),
(2, 1, 2, '2013-05-30 19:37:06'),
(3, 1, 2, '2013-05-30 19:37:09'),
(1, 1, 2, '2013-05-30 19:37:11'),
(2, 1, 2, '2013-05-30 19:37:14'),
(3, 1, 2, '2013-05-30 19:37:16'),
(3, 1, 2, '2013-05-31 07:03:40'),
(2, 1, 2, '2013-05-31 07:21:06'),
(1, 1, 2, '2013-06-01 20:05:03'),
(2, 1, 2, '2013-06-08 17:36:24'),
(3, 1, 2, '2013-06-08 17:56:03'),
(1, 1, 2, '2013-06-08 18:51:14'),
(3, 1, 2, '2013-06-10 10:26:43'),
(3, 1, 2, '2013-06-10 13:38:53'),
(2, 1, 2, '2013-06-11 12:49:22'),
(1, 1, 2, '2013-06-13 21:32:48'),
(2, 1, 2, '2013-06-14 07:37:57'),
(1, 1, 2, '2013-06-14 07:37:58'),
(3, 1, 2, '2013-06-14 07:37:59'),
(2, 1, 2, '2013-06-14 07:38:01'),
(1, 1, 2, '2013-06-14 07:38:02'),
(3, 1, 2, '2013-06-14 07:38:02'),
(1, 1, 2, '2013-06-14 08:24:28'),
(3, 1, 2, '2013-06-14 08:24:28'),
(3, 1, 2, '2013-06-14 08:24:29'),
(1, 1, 2, '2013-06-14 08:24:30'),
(2, 1, 2, '2013-06-14 08:24:31'),
(2, 1, 2, '2013-06-14 08:24:32'),
(1, 1, 2, '2013-06-14 11:43:44'),
(3, 1, 2, '2013-06-15 10:41:08'),
(1, 1, 2, '2013-06-16 02:23:02'),
(2, 1, 2, '2013-06-16 02:23:12'),
(3, 1, 2, '2013-06-16 02:23:23'),
(1, 1, 2, '2013-06-16 02:23:38'),
(2, 1, 2, '2013-06-16 02:24:04'),
(3, 1, 2, '2013-06-16 02:24:26'),
(1, 1, 2, '2013-06-18 01:15:17'),
(1, 1, 2, '2013-06-21 09:17:53'),
(1, 1, 2, '2013-06-21 14:09:33'),
(3, 1, 2, '2013-06-21 23:20:27'),
(2, 1, 2, '2013-06-23 17:02:31'),
(2, 1, 2, '2013-06-23 21:36:20'),
(3, 1, 2, '2013-06-25 17:35:09'),
(1, 1, 2, '2013-06-26 15:34:35'),
(2, 1, 2, '2013-06-27 14:50:09'),
(1, 1, 2, '2013-06-28 03:15:00'),
(1, 1, 2, '2013-06-29 01:45:58'),
(3, 1, 2, '2013-06-29 10:49:52'),
(1, 1, 2, '2013-07-01 10:43:50'),
(2, 1, 2, '2013-07-01 10:43:53'),
(3, 1, 2, '2013-07-01 10:43:56'),
(1, 1, 2, '2013-07-01 10:43:59'),
(2, 1, 2, '2013-07-01 10:44:01'),
(3, 1, 2, '2013-07-01 10:44:03'),
(1, 1, 2, '2013-07-02 20:43:52'),
(1, 1, 2, '2013-07-03 13:45:09'),
(2, 1, 2, '2013-07-03 13:45:13'),
(3, 1, 2, '2013-07-03 13:45:18'),
(1, 1, 2, '2013-07-03 13:45:24'),
(2, 1, 2, '2013-07-03 13:45:26'),
(3, 1, 2, '2013-07-03 13:45:29'),
(1, 1, 2, '2013-07-03 19:20:35'),
(2, 1, 2, '2013-07-03 19:20:36'),
(3, 1, 2, '2013-07-03 19:20:37'),
(1, 1, 2, '2013-07-03 19:20:39'),
(2, 1, 2, '2013-07-03 19:20:44'),
(3, 1, 2, '2013-07-03 19:20:51'),
(1, 1, 2, '2013-07-05 02:39:27'),
(1, 1, 2, '2013-07-06 17:34:12'),
(2, 1, 2, '2013-07-06 17:34:14'),
(3, 1, 2, '2013-07-06 17:34:15'),
(1, 1, 2, '2013-07-06 17:34:17'),
(2, 1, 2, '2013-07-06 17:34:30'),
(3, 1, 2, '2013-07-06 17:34:33'),
(1, 1, 2, '2013-07-08 08:38:42'),
(3, 1, 2, '2013-07-08 12:08:16'),
(2, 1, 2, '2013-07-08 15:54:08'),
(2, 1, 2, '2013-07-08 15:54:11'),
(1, 1, 2, '2013-07-08 15:54:17'),
(3, 1, 2, '2013-07-08 15:54:23'),
(1, 1, 2, '2013-07-08 15:54:29'),
(3, 1, 2, '2013-07-08 15:54:37'),
(2, 1, 2, '2013-07-08 19:57:52'),
(2, 1, 2, '2013-07-09 05:47:58'),
(3, 1, 2, '2013-07-10 02:03:22'),
(2, 1, 2, '2013-07-11 11:30:16'),
(1, 1, 2, '2013-07-12 19:53:40'),
(3, 1, 2, '2013-07-12 20:57:42'),
(2, 1, 2, '2013-07-13 00:08:05'),
(3, 1, 2, '2013-07-13 04:15:58'),
(2, 1, 2, '2013-07-13 04:18:14'),
(1, 1, 2, '2013-07-13 04:20:33'),
(3, 1, 2, '2013-07-13 04:22:42'),
(2, 1, 2, '2013-07-13 04:25:07'),
(1, 1, 2, '2013-07-13 04:27:22'),
(1, 1, 2, '2013-07-13 08:21:29'),
(1, 1, 2, '2013-07-14 09:26:45'),
(2, 1, 2, '2013-07-14 10:22:12'),
(3, 1, 2, '2013-07-14 15:17:09'),
(1, 1, 2, '2013-07-16 11:10:18'),
(3, 1, 2, '2013-07-16 23:32:22'),
(2, 1, 2, '2013-07-16 23:48:46'),
(1, 1, 2, '2013-07-17 00:01:36'),
(2, 1, 2, '2013-07-17 00:41:32'),
(1, 1, 2, '2013-07-17 01:04:51'),
(3, 1, 2, '2013-07-17 01:04:58'),
(1, 1, 2, '2013-07-18 00:40:22'),
(2, 1, 2, '2013-07-18 00:43:41'),
(2, 1, 2, '2013-07-18 01:00:01'),
(3, 1, 2, '2013-07-18 01:26:52'),
(1, 1, 2, '2013-07-18 01:27:20'),
(3, 1, 2, '2013-07-18 01:46:48'),
(2, 1, 2, '2013-07-19 00:39:00'),
(1, 1, 2, '2013-07-19 01:07:58'),
(3, 1, 2, '2013-07-19 01:09:47'),
(1, 1, 2, '2013-07-19 01:19:06'),
(3, 1, 2, '2013-07-19 01:26:35'),
(2, 1, 2, '2013-07-19 01:27:06'),
(1, 1, 2, '2013-07-19 07:27:23'),
(2, 1, 2, '2013-07-19 07:27:26'),
(3, 1, 2, '2013-07-19 07:27:29'),
(1, 1, 2, '2013-07-19 07:27:50'),
(2, 1, 2, '2013-07-19 07:28:05'),
(3, 1, 2, '2013-07-19 07:28:18'),
(1, 1, 2, '2013-07-19 23:25:39'),
(3, 1, 2, '2013-07-20 00:22:23'),
(2, 1, 2, '2013-07-20 00:22:25'),
(1, 1, 2, '2013-07-20 00:33:29'),
(2, 1, 2, '2013-07-20 00:48:23'),
(3, 1, 2, '2013-07-20 01:13:51'),
(1, 1, 2, '2013-07-22 06:44:12'),
(2, 1, 2, '2013-07-22 06:44:15'),
(3, 1, 2, '2013-07-22 06:44:16'),
(1, 1, 2, '2013-07-22 06:44:18'),
(2, 1, 2, '2013-07-22 06:44:21'),
(3, 1, 2, '2013-07-22 06:44:24'),
(2, 1, 2, '2013-07-24 09:39:17'),
(3, 1, 2, '2013-07-24 18:11:11'),
(1, 1, 2, '2013-07-24 18:22:23'),
(2, 1, 2, '2013-07-24 23:53:37'),
(2, 1, 2, '2013-07-25 00:15:04'),
(1, 1, 2, '2013-07-25 01:12:07'),
(3, 1, 2, '2013-07-25 01:22:08'),
(1, 1, 2, '2013-07-25 17:02:50'),
(3, 1, 2, '2013-07-25 18:50:41'),
(2, 1, 2, '2013-07-25 18:55:21'),
(1, 1, 2, '2013-07-25 19:14:54'),
(3, 1, 2, '2013-07-25 19:17:15'),
(3, 1, 2, '2013-07-25 19:19:16'),
(1, 1, 2, '2013-07-25 19:26:28'),
(2, 1, 2, '2013-07-25 19:26:31'),
(3, 1, 2, '2013-07-25 19:26:33'),
(1, 1, 2, '2013-07-25 19:26:35'),
(2, 1, 2, '2013-07-25 19:26:39'),
(3, 1, 2, '2013-07-25 19:26:42'),
(2, 1, 2, '2013-07-25 21:48:31'),
(3, 1, 2, '2013-07-26 02:10:16'),
(2, 1, 2, '2013-07-26 02:47:13'),
(1, 1, 2, '2013-07-26 03:08:32'),
(2, 1, 2, '2013-07-26 03:40:53'),
(1, 1, 2, '2013-07-26 04:02:46'),
(3, 1, 2, '2013-07-26 05:41:27'),
(3, 1, 2, '2013-07-26 08:24:11'),
(3, 1, 2, '2013-07-26 21:47:22'),
(2, 1, 2, '2013-07-26 23:41:49'),
(1, 1, 2, '2013-07-27 02:42:52'),
(1, 1, 2, '2013-07-27 09:21:41'),
(3, 1, 2, '2013-07-27 09:45:32'),
(2, 1, 2, '2013-07-27 15:37:24'),
(2, 1, 2, '2013-07-27 18:52:36'),
(1, 1, 2, '2013-07-27 23:56:31'),
(3, 1, 2, '2013-07-28 09:42:54'),
(2, 1, 2, '2013-07-28 19:49:09'),
(3, 1, 2, '2013-07-29 00:23:43'),
(1, 1, 2, '2013-07-29 02:07:58'),
(3, 1, 2, '2013-07-29 07:23:10'),
(1, 1, 2, '2013-07-29 10:26:38'),
(1, 1, 2, '2013-07-29 13:25:13'),
(2, 1, 2, '2013-07-29 20:25:45'),
(1, 1, 2, '2013-07-30 08:52:16'),
(1, 1, 2, '2013-07-31 03:40:27'),
(2, 1, 2, '2013-07-31 04:15:47'),
(2, 1, 2, '2013-07-31 04:40:57'),
(1, 1, 2, '2013-07-31 07:34:05'),
(2, 1, 2, '2013-07-31 07:34:11'),
(3, 1, 2, '2013-07-31 07:34:14'),
(3, 1, 2, '2013-07-31 09:28:32'),
(1, 1, 2, '2013-07-31 17:15:21'),
(3, 1, 2, '2013-07-31 18:00:42'),
(2, 1, 2, '2013-07-31 18:17:36'),
(3, 1, 2, '2013-07-31 19:28:52'),
(1, 1, 2, '2013-07-31 19:57:34'),
(2, 1, 2, '2013-07-31 22:15:28'),
(3, 1, 2, '2013-07-31 22:53:21'),
(1, 1, 2, '2013-08-01 14:52:28'),
(3, 1, 2, '2013-08-01 15:46:03'),
(2, 1, 2, '2013-08-01 18:33:02'),
(1, 1, 2, '2013-08-01 21:31:38'),
(3, 1, 2, '2013-08-02 03:15:20'),
(2, 1, 2, '2013-08-02 06:53:58'),
(1, 1, 2, '2013-08-04 13:35:16'),
(1, 1, 2, '2013-08-06 18:31:37'),
(2, 1, 2, '2013-08-07 01:13:17'),
(2, 1, 2, '2013-08-09 04:03:26'),
(2, 1, 2, '2013-08-09 05:44:25'),
(3, 1, 2, '2013-08-09 05:56:38'),
(3, 1, 2, '2013-08-09 06:12:29'),
(1, 1, 2, '2013-08-09 07:49:39'),
(1, 1, 2, '2013-08-09 07:51:25'),
(2, 1, 2, '2013-08-11 16:55:20'),
(3, 1, 2, '2013-08-12 08:25:28'),
(2, 1, 2, '2013-08-16 03:41:59'),
(3, 1, 2, '2013-08-16 04:49:26'),
(1, 1, 2, '2013-08-16 06:04:20'),
(1, 1, 2, '2013-08-20 12:10:37'),
(2, 1, 2, '2013-08-20 12:10:53'),
(3, 1, 2, '2013-08-20 12:11:02'),
(1, 1, 2, '2013-08-20 12:11:09'),
(1, 1, 2, '2013-08-20 13:17:03'),
(1, 1, 2, '2013-08-21 08:25:49'),
(2, 1, 2, '2013-08-21 08:25:53'),
(3, 1, 2, '2013-08-21 08:25:57'),
(1, 1, 2, '2013-08-21 08:26:01'),
(2, 1, 2, '2013-08-21 08:26:04'),
(3, 1, 2, '2013-08-21 08:26:07'),
(2, 1, 2, '2013-08-21 18:12:17'),
(3, 1, 2, '2013-08-22 05:27:07'),
(1, 1, 2, '2013-08-22 14:39:13'),
(2, 1, 2, '2013-08-23 04:46:02'),
(1, 1, 2, '2013-08-23 16:15:40'),
(3, 1, 2, '2013-08-23 23:05:31'),
(2, 1, 2, '2013-08-24 11:51:52'),
(3, 1, 2, '2013-08-25 11:38:31'),
(2, 1, 2, '2013-08-25 12:59:28'),
(1, 1, 2, '2013-08-25 13:14:12'),
(3, 1, 2, '2013-08-25 20:47:39'),
(1, 1, 2, '2013-08-26 01:09:51'),
(3, 1, 2, '2013-08-26 22:35:12'),
(2, 1, 2, '2013-08-27 09:54:45'),
(1, 1, 2, '2013-08-27 19:38:41'),
(2, 1, 2, '2013-08-27 19:38:47'),
(3, 1, 2, '2013-08-27 19:38:53'),
(1, 1, 2, '2013-08-27 19:39:17'),
(2, 1, 2, '2013-08-27 19:39:29'),
(1, 1, 2, '2013-08-29 10:40:03'),
(3, 1, 2, '2013-08-29 15:24:31'),
(2, 1, 2, '2013-08-30 02:06:14'),
(1, 1, 2, '2013-08-30 03:13:00'),
(2, 1, 2, '2013-08-30 03:46:23'),
(1, 1, 2, '2013-08-30 04:31:51'),
(3, 1, 2, '2013-08-30 09:13:37'),
(3, 1, 2, '2013-08-30 19:24:43'),
(1, 1, 2, '2013-08-31 00:06:25'),
(2, 1, 2, '2013-08-31 00:06:26'),
(3, 1, 2, '2013-08-31 00:06:33'),
(1, 1, 2, '2013-08-31 00:06:36'),
(2, 1, 2, '2013-08-31 00:06:38'),
(3, 1, 2, '2013-08-31 00:06:41'),
(2, 1, 2, '2013-08-31 06:01:46'),
(1, 1, 2, '2013-09-01 09:25:33'),
(1, 1, 2, '2013-09-01 12:28:03'),
(1, 1, 2, '2013-09-02 21:36:02'),
(2, 1, 2, '2013-09-02 21:36:07'),
(3, 1, 2, '2013-09-02 21:36:09'),
(1, 1, 2, '2013-09-02 21:36:12'),
(2, 1, 2, '2013-09-02 21:36:19'),
(3, 1, 2, '2013-09-02 21:36:26'),
(3, 1, 2, '2013-09-03 00:31:11'),
(2, 1, 2, '2013-09-03 03:50:47'),
(1, 1, 2, '2013-09-03 11:04:20'),
(2, 1, 2, '2013-09-03 14:58:06'),
(2, 1, 2, '2013-09-03 17:51:14'),
(1, 1, 2, '2013-09-04 02:57:15'),
(2, 1, 2, '2013-09-04 13:25:42'),
(3, 1, 2, '2013-09-04 13:36:27'),
(3, 1, 2, '2013-09-04 17:05:19'),
(3, 1, 2, '2013-09-04 20:25:47'),
(2, 1, 2, '2013-09-05 02:49:28'),
(1, 1, 2, '2013-09-05 03:15:15'),
(1, 1, 2, '2013-09-05 09:41:44'),
(2, 1, 2, '2013-09-05 09:48:16'),
(3, 1, 2, '2013-09-06 13:27:07'),
(1, 1, 2, '2013-09-07 02:48:03'),
(3, 1, 2, '2013-09-07 13:18:01');

-- --------------------------------------------------------

--
-- Table structure for table `orion_stattotals`
--

CREATE TABLE `orion_stattotals` (
  `itemid` int(11) NOT NULL,
  `stattype` tinyint(4) DEFAULT NULL,
  `useraction` tinyint(4) DEFAULT NULL,
  `monthvalue` tinyint(4) DEFAULT NULL,
  `yearvalue` tinyint(4) DEFAULT NULL,
  `monthtotal` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orion_tags`
--

CREATE TABLE `orion_tags` (
  `tagid` int(11) NOT NULL,
  `tagname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `taghand` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `isapproved` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_tags`
--

INSERT INTO `orion_tags` (`tagid`, `tagname`, `taghand`, `isapproved`) VALUES
(1, 'adf', 'adf', 0),
(2, 'menaa', 'menaa', 0),
(3, 'fadkaf', 'fadkaf', 0),
(4, 'dsaf', 'dsaf', 0),
(5, 'test', 'test', 0),
(6, 'sony ericcson', 'sony-ericcson', 0),
(7, 'htc', 'htc', 0),
(8, 'touch', 'touch', 0),
(9, 'diamond2', 'diamond2', 0),
(10, 'apple', 'apple', 0),
(11, 'iphone', 'iphone', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orion_users`
--

CREATE TABLE `orion_users` (
  `userid` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwd` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `postcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` smallint(4) UNSIGNED DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bankname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bankaccount` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxoffice` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxnumber` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billaddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `billpostcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billcity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billcountry` smallint(4) UNSIGNED DEFAULT NULL,
  `billphone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billfax` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `isdeleted` tinyint(4) NOT NULL,
  `dtcreated` datetime NOT NULL,
  `dtdeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dtmodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dtlastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ticket` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_users`
--

INSERT INTO `orion_users` (`userid`, `email`, `username`, `passwd`, `salt`, `firstname`, `lastname`, `address`, `postcode`, `city`, `country`, `phone`, `bankname`, `bankaccount`, `taxoffice`, `taxnumber`, `billname`, `billaddress`, `billpostcode`, `billcity`, `billcountry`, `billphone`, `billfax`, `notes`, `isdeleted`, `dtcreated`, `dtdeleted`, `dtmodified`, `dtlastlogin`, `ticket`) VALUES
(1, 'uzaytek@gmail.com', 'uzaytek', 'e1b3e53d46803b9f02db49745a60cb87', 'nNp', 'Aydin', 'Uzun', 'Rüzgarlı Cad. No:15 Ulus', '06090', 'Ankara', 215, '+90-312-3104304', NULL, NULL, NULL, NULL, 'Aydin Uzun', 'Rüzgarlı Cad. No:15 Ulus', '06090', 'Ankara', NULL, '+90-312-3104304', NULL, NULL, 1, '2012-03-26 15:25:10', '2012-05-07 11:21:40', '2012-03-26 12:25:10', '2012-03-26 12:25:10', NULL),
(2, 'aydin.uzun@uzaytek.com', 'demo', '766bd63ccc85dd1a801b4f4ecbf3eb4a', 'bZ4', 'test', 'test', 'türkçe karakterler niye bozuk çıktı ıüliıöçıı\r\n\r\nsadece ı da problem yapıyordu', '06090', 'İstanbul', 215, '+90-312-3104304', NULL, NULL, NULL, NULL, 'fatura adı', 'fatura adresi', '94534', 'şehir', 215, '+90-312-3104304', NULL, NULL, 1, '2012-03-26 16:23:36', '2012-05-07 11:21:23', '2012-03-26 13:23:36', '2012-03-26 13:23:36', NULL),
(3, 'uzaytek@gmail.com', 'test 3', '51ca2b629afc373600f18f750dd89718', 'uKK', 'Aydin', 'Uzun', 'test', '32424', 'tesat', 198, '324234234234234', NULL, NULL, NULL, NULL, 'dfadfad asd fasdf asd', 'asdf adsf adsfsa asd fasd', '34242', '234234', 197, '32432423423423', NULL, NULL, 1, '2012-05-01 10:09:01', '2012-05-01 07:14:21', '2012-05-01 07:09:01', '2012-05-01 07:09:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orion_usersebulletin`
--

CREATE TABLE `orion_usersebulletin` (
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `a1` varchar(32) COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orion_usersebulletin`
--

INSERT INTO `orion_usersebulletin` (`email`, `name`, `a1`) VALUES
('uzaytek@gmail.com', 'Aydın Uzun', 'adfadsf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orion_admins`
--
ALTER TABLE `orion_admins`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `orion_banners`
--
ALTER TABLE `orion_banners`
  ADD PRIMARY KEY (`fileid`);

--
-- Indexes for table `orion_baskets`
--
ALTER TABLE `orion_baskets`
  ADD PRIMARY KEY (`basketid`),
  ADD KEY `productid` (`productid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `paymentid` (`paymentid`);

--
-- Indexes for table `orion_categories`
--
ALTER TABLE `orion_categories`
  ADD PRIMARY KEY (`catid`),
  ADD KEY `parentcatid` (`parentcatid`);

--
-- Indexes for table `orion_countries`
--
ALTER TABLE `orion_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orion_faqs`
--
ALTER TABLE `orion_faqs`
  ADD PRIMARY KEY (`faqid`);

--
-- Indexes for table `orion_files`
--
ALTER TABLE `orion_files`
  ADD PRIMARY KEY (`fileid`),
  ADD KEY `fkid` (`fkid`);

--
-- Indexes for table `orion_globals`
--
ALTER TABLE `orion_globals`
  ADD PRIMARY KEY (`globalid`);

--
-- Indexes for table `orion_logs`
--
ALTER TABLE `orion_logs`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `orion_news`
--
ALTER TABLE `orion_news`
  ADD PRIMARY KEY (`newsid`);

--
-- Indexes for table `orion_newsletters`
--
ALTER TABLE `orion_newsletters`
  ADD PRIMARY KEY (`letterid`);

--
-- Indexes for table `orion_paymentaccounts`
--
ALTER TABLE `orion_paymentaccounts`
  ADD PRIMARY KEY (`accountid`);

--
-- Indexes for table `orion_paymentgateways`
--
ALTER TABLE `orion_paymentgateways`
  ADD PRIMARY KEY (`gateid`);

--
-- Indexes for table `orion_payments`
--
ALTER TABLE `orion_payments`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `orion_productimages`
--
ALTER TABLE `orion_productimages`
  ADD PRIMARY KEY (`imgid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `orion_products`
--
ALTER TABLE `orion_products`
  ADD PRIMARY KEY (`productid`),
  ADD KEY `catid` (`catid`);

--
-- Indexes for table `orion_quemail`
--
ALTER TABLE `orion_quemail`
  ADD PRIMARY KEY (`queid`);

--
-- Indexes for table `orion_sessions`
--
ALTER TABLE `orion_sessions`
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `orion_stattotals`
--
ALTER TABLE `orion_stattotals`
  ADD PRIMARY KEY (`itemid`);

--
-- Indexes for table `orion_tags`
--
ALTER TABLE `orion_tags`
  ADD PRIMARY KEY (`tagid`);

--
-- Indexes for table `orion_users`
--
ALTER TABLE `orion_users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orion_admins`
--
ALTER TABLE `orion_admins`
  MODIFY `adminid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orion_banners`
--
ALTER TABLE `orion_banners`
  MODIFY `fileid` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orion_baskets`
--
ALTER TABLE `orion_baskets`
  MODIFY `basketid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orion_categories`
--
ALTER TABLE `orion_categories`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orion_countries`
--
ALTER TABLE `orion_countries`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `orion_faqs`
--
ALTER TABLE `orion_faqs`
  MODIFY `faqid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orion_files`
--
ALTER TABLE `orion_files`
  MODIFY `fileid` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orion_globals`
--
ALTER TABLE `orion_globals`
  MODIFY `globalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orion_logs`
--
ALTER TABLE `orion_logs`
  MODIFY `logid` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orion_news`
--
ALTER TABLE `orion_news`
  MODIFY `newsid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orion_newsletters`
--
ALTER TABLE `orion_newsletters`
  MODIFY `letterid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orion_paymentaccounts`
--
ALTER TABLE `orion_paymentaccounts`
  MODIFY `accountid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orion_paymentgateways`
--
ALTER TABLE `orion_paymentgateways`
  MODIFY `gateid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orion_payments`
--
ALTER TABLE `orion_payments`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orion_productimages`
--
ALTER TABLE `orion_productimages`
  MODIFY `imgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orion_products`
--
ALTER TABLE `orion_products`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orion_quemail`
--
ALTER TABLE `orion_quemail`
  MODIFY `queid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orion_tags`
--
ALTER TABLE `orion_tags`
  MODIFY `tagid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orion_users`
--
ALTER TABLE `orion_users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
