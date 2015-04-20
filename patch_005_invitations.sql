
CREATE TABLE IF NOT EXISTS `invitations` (
`invitation_id` int(11) NOT NULL,
  `host_email` varchar(200) COLLATE utf8_bin NOT NULL,
  `guest_email` varchar(200) COLLATE utf8_bin NOT NULL,
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `created_datetime` datetime NOT NULL,
  `expired_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE `invitations`
 ADD PRIMARY KEY (`invitation_id`), ADD UNIQUE KEY `host_email` (`host_email`,`guest_email`);

ALTER TABLE `invitations`
MODIFY `invitation_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
