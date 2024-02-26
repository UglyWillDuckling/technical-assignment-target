
-- Generic tables

CREATE TABLE domain_events (
  id CHAR(36) NOT NULL,
  aggregate_id CHAR(36) NOT NULL,
  name VARCHAR(255) NOT NULL,
  body JSON NOT NULL,
  occurred_on TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- Aggregates tables

CREATE TABLE targetadds_cart_removal (
	id CHAR(36) NOT NULL,
	cart_id CHAR(36) NOT NULL,
	sku VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE targetadds_dropped_items (
  id CHAR(36) NOT NULL,
  customer_id CHAR(36) NOT NULL,
  sku VARCHAR(255) NOT NULL,
  created_at DATE NOT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

CREATE INDEX `targetadds_dropped_items__sku-customer_id`
ON targetadds_dropped_items (sku, customer_id)
COMMENT 'sku and customer_id index';
CREATE INDEX targetadds_dropped_items__index_sku
ON targetadds_dropped_items (sku)
COMMENT 'Sku Index';
CREATE INDEX `targetadds_dropped_items__index-created_at`
ON targetadds_dropped_items (created_at)
COMMENT 'Created At Index';

