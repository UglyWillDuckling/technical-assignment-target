
-- Aggregates tables

CREATE TABLE targetadds_cart_removal (
	id CHAR(36) NOT NULL,
	cart_id VARCHAR(36) NOT NULL,
	customer_id VARCHAR(36) NOT NULL,
	sku VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

