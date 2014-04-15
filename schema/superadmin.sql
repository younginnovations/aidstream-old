/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user` (
	  `user_id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_name` varchar(255) NOT NULL,
	  `role_id` int(10) NOT NULL,
	  `email` varchar(100) DEFAULT NULL,
	  `password` varchar(50) NOT NULL,
	  `account_id` int(10) NOT NULL,
	  `status` int(2) NOT NULL,
	  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'superadmin',3,'admin@mail.com','964980fb514fb6c74369759fde2062a1',0,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `profile` WRITE;
INSERT INTO `profile` VALUES (NULL ,'Superadmin', NULL , '' ,  '1');
UNLOCK TABLES;

