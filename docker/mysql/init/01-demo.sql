CREATE DATABASE IF NOT EXISTS zaomicron_demo;
CREATE DATABASE IF NOT EXISTS zaomicron_test;
GRANT ALL PRIVILEGES ON zaomicron_demo.* TO 'zaomicron'@'%';
GRANT ALL PRIVILEGES ON zaomicron_test.* TO 'zaomicron'@'%';
FLUSH PRIVILEGES;

