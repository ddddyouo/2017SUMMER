#	问题清单
*	使用mcrypt_create_iv()函数报错
	*	在ubuntu系统中安装mcrypt
	*	sudo apt-get install libmcrypt4 php-mcrypt 
*	数据库存储中文乱码
	*	加上$link->query('SET NAMES UTF8');语句
*	生成服务器公钥
	*	在/etc/apache2/ssl里打开终端
	*	sudo openssl rsa -in apache.key(服务器私钥) -pubout -out apache_pub.key(服务器公钥)
