-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 05 Kas 2018, 16:20:41
-- Sunucu sürümü: 5.7.24
-- PHP Sürümü: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `stonesiv_iveist`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_groups`
--

CREATE TABLE `aauth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_groups`
--

INSERT INTO `aauth_groups` (`id`, `name`, `definition`) VALUES
(0, 'Master', 'Master of Admins'),
(1, 'Admin', 'Super Admin Group'),
(2, 'Public', 'Public Access Group'),
(3, 'Default', 'Default Access Group'),
(4, 'registrars', 'Registrations'),
(5, 'iletisimve.istanbul', 'İletişimve İstanbul Ajansı yönetimindeki kullanıcılar'),
(202, 'k0o8oo8k8ogs4wc4o0484g0ccssggccock4gkkgk', 'token'),
(248, '8wgwg00kgk8kgw8cw4w04oc044cws8o0o0o8oc4s', 'token'),
(263, 'wks04wo4scwok84ccowkoo4k8s4s0owcggg8g08c', 'token'),
(266, 'c8sgcw00w048ksokgo0kso8g48gckw44g4oosgg4', 'token');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_group_to_group`
--

CREATE TABLE `aauth_group_to_group` (
  `group_id` int(11) UNSIGNED NOT NULL,
  `subgroup_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_login_attempts`
--

CREATE TABLE `aauth_login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(39) DEFAULT '0',
  `timestamp` datetime DEFAULT NULL,
  `login_attempts` tinyint(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `aauth_login_attempts`
--

INSERT INTO `aauth_login_attempts` (`id`, `ip_address`, `timestamp`, `login_attempts`) VALUES
(1, '46.196.233.145', '2016-11-30 19:30:04', 9);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_perms`
--

CREATE TABLE `aauth_perms` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_perms`
--

INSERT INTO `aauth_perms` (`id`, `name`, `definition`) VALUES
(1, 'register', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_perm_to_group`
--

CREATE TABLE `aauth_perm_to_group` (
  `perm_id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_perm_to_user`
--

CREATE TABLE `aauth_perm_to_user` (
  `perm_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_perm_to_user`
--

INSERT INTO `aauth_perm_to_user` (`perm_id`, `user_id`) VALUES
(1, 11),
(1, 17);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_pms`
--

CREATE TABLE `aauth_pms` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text,
  `date_sent` datetime DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  `pm_deleted_sender` int(1) DEFAULT NULL,
  `pm_deleted_receiver` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_pms`
--

INSERT INTO `aauth_pms` (`id`, `sender_id`, `receiver_id`, `title`, `message`, `date_sent`, `date_read`, `pm_deleted_sender`, `pm_deleted_receiver`) VALUES
(1, 0, 2, 'deneme@deneme.com', 'deneme<br>\\n<br>\\ndeneme', '2017-01-07 11:02:13', NULL, NULL, NULL),
(2, 0, 2, 'deneme@deneme.com', 'deneme<br>\\n<br>\\ndeneme', '2017-01-07 11:37:16', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_users`
--

CREATE TABLE `aauth_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `banned` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `forgot_exp` text,
  `remember_time` datetime DEFAULT NULL,
  `remember_exp` text,
  `verification_code` text,
  `totp_secret` varchar(16) DEFAULT NULL,
  `ip_address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_users`
--

INSERT INTO `aauth_users` (`id`, `email`, `pass`, `username`, `banned`, `last_login`, `last_activity`, `date_created`, `forgot_exp`, `remember_time`, `remember_exp`, `verification_code`, `totp_secret`, `ip_address`) VALUES
(2, 'ibrahimuslu@gmail.com', 'test', 'Ibrahim USLU', 0, '2018-10-21 20:23:31', '2018-10-21 20:23:31', '2016-12-01 00:00:00', NULL, NULL, NULL, 'asdasfsafasgas', NULL, '217.131.81.110');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_user_to_group`
--

CREATE TABLE `aauth_user_to_group` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_user_to_group`
--

INSERT INTO `aauth_user_to_group` (`user_id`, `group_id`) VALUES
(2, 0),
(2, 1),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 266),
(10, 3),
(10, 5),
(11, 3),
(11, 4),
(16, 3),
(16, 5),
(16, 263),
(17, 3),
(17, 4),
(22, 3),
(22, 5),
(23, 3),
(23, 4),
(24, 3),
(24, 4),
(25, 3),
(25, 4),
(26, 3),
(26, 5),
(27, 3),
(27, 5),
(28, 3),
(28, 5),
(29, 3),
(29, 5),
(30, 1),
(30, 3),
(30, 5),
(30, 6),
(31, 3),
(31, 5),
(31, 248),
(32, 3),
(32, 5),
(33, 3),
(33, 5),
(34, 3),
(34, 6),
(35, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(39, 5),
(39, 7),
(40, 3),
(41, 3),
(41, 4),
(41, 5),
(41, 6);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aauth_user_variables`
--

CREATE TABLE `aauth_user_variables` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `data_key` varchar(100) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `aauth_user_variables`
--

INSERT INTO `aauth_user_variables` (`id`, `user_id`, `data_key`, `value`) VALUES
(10, 2, 'domains', '[{\"name\":\"iletisimve.istanbul\",\"hosting\":\"17\",\"registrar\":\"\"}]'),
(16, 17, 'account', '{\"api\":\"cpanel_api\",\"protocol\":\"https\",\"host\":\"pro05.ni.net.tr\",\"port\":\"2083\",\"api_key\":\"\",\"username\":\"stonesiv\",\"password\":\"password\"}'),
(17, 11, 'account', '{\"api\":\"internetbs_api\",\"protocol\":\"https\",\"host\":\"api.internet.bs\",\"port\":\"\",\"api_key\":\"api_key\",\"username\":\"\",\"password\":\"password\"}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ive_keys`
--

CREATE TABLE `ive_keys` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ive_keys`
--

INSERT INTO `ive_keys` (`id`, `key`, `level`, `ignore_limits`, `date_created`) VALUES
(273, '8wgwg00kgk8kgw8cw4w04oc044cws8o0o0o8oc4s', 1, 1, 1483823879),
(287, 'wks04wo4scwok84ccowkoo4k8s4s0owcggg8g08c', 1, 1, 1484583648),
(290, 'c8sgcw00w048ksokgo0kso8g48gckw44g4oosgg4', 1, 1, 1485354281);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ive_logs`
--

CREATE TABLE `ive_logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ive_logs`
--

INSERT INTO `ive_logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(1, 'api/example/users', 'get', '{\"Host\":\"iletisimve.istanbul\",\"Connection\":\"keep-alive\",\"Pragma\":\"no-cache\",\"Cache-Control\":\"no-cache\",\"Upgrade-Insecure-Requests\":\"1\",\"User-Agent\":\"Mozilla\\/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit\\/601.1.46 (KHTML, like Gecko) Version\\/9.0 Mobile\\/13B143 Safari\\/601.1\",\"Accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/webp,*\\/*;q=0.8\",\"Referer\":\"http:\\/\\/iletisimve.istanbul\\/dev\\/rest-server\",\"Accept-Encoding\":\"gzip, deflate, sdch\",\"Accept-Language\":\"tr-TR,tr;q=0.8,en-US;q=0.6,en;q=0.4\",\"Cookie\":\"language=tr; currency=TRY; _ga=GA1.2.1118779421.1480488098; ciNav=no; ci_session=4smoidrb83g1k6464vfmaheqgofemlqh\"}', '', '212.252.83.27', 1482011473, 0.194904, '1', 200),;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `aauth_groups`
--
ALTER TABLE `aauth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `aauth_group_to_group`
--
ALTER TABLE `aauth_group_to_group`
  ADD PRIMARY KEY (`group_id`,`subgroup_id`);

--
-- Tablo için indeksler `aauth_login_attempts`
--
ALTER TABLE `aauth_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `aauth_perms`
--
ALTER TABLE `aauth_perms`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `aauth_perm_to_group`
--
ALTER TABLE `aauth_perm_to_group`
  ADD PRIMARY KEY (`perm_id`,`group_id`);

--
-- Tablo için indeksler `aauth_perm_to_user`
--
ALTER TABLE `aauth_perm_to_user`
  ADD PRIMARY KEY (`perm_id`,`user_id`);

--
-- Tablo için indeksler `aauth_pms`
--
ALTER TABLE `aauth_pms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `full_index` (`id`,`sender_id`,`receiver_id`,`date_read`);

--
-- Tablo için indeksler `aauth_users`
--
ALTER TABLE `aauth_users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `aauth_user_to_group`
--
ALTER TABLE `aauth_user_to_group`
  ADD PRIMARY KEY (`user_id`,`group_id`);

--
-- Tablo için indeksler `aauth_user_variables`
--
ALTER TABLE `aauth_user_variables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_index` (`user_id`);

--
-- Tablo için indeksler `ive_keys`
--
ALTER TABLE `ive_keys`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ive_logs`
--
ALTER TABLE `ive_logs`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `aauth_groups`
--
ALTER TABLE `aauth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- Tablo için AUTO_INCREMENT değeri `aauth_login_attempts`
--
ALTER TABLE `aauth_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=731;

--
-- Tablo için AUTO_INCREMENT değeri `aauth_perms`
--
ALTER TABLE `aauth_perms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `aauth_pms`
--
ALTER TABLE `aauth_pms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `aauth_users`
--
ALTER TABLE `aauth_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Tablo için AUTO_INCREMENT değeri `aauth_user_variables`
--
ALTER TABLE `aauth_user_variables`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `ive_keys`
--
ALTER TABLE `ive_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- Tablo için AUTO_INCREMENT değeri `ive_logs`
--
ALTER TABLE `ive_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6877;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
