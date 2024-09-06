CREATE DATABASE `telus_test` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE telus_test.header_process (
	id INT auto_increment NOT NULL,
	file_name varchar(100) NOT NULL,
	execution_date DATE NOT NULL,
	CONSTRAINT header_process_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;


CREATE TABLE telus_test.detail_process (
	id INT auto_increment NOT NULL,
	`data` json NOT NULL,
	header_process_id INT NOT NULL,
	CONSTRAINT detail_process_pk PRIMARY KEY (id),
	CONSTRAINT detail_process_header_process_FK FOREIGN KEY (header_process_id) REFERENCES telus_test.header_process(id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;
