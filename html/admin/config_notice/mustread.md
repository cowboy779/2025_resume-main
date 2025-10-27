# 🧑‍💻 PHP

> ##error_log 안뜰때 중요 ✨  

PHP 설정파일 (php.ini) 에서  

```
php.ini
display_errors = On, 
log_errors = On
```

**중요!** /etc/php-fpm.d/www.conf   (php.ini 보다 우선순위 높음)
```
php_flag[display_errors] = off
php_admin_value[error_log] = /var/log/php-fpm/www-error.log
```





---