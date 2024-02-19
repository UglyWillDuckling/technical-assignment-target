#!/usr/bin/env bash

bin/mysql -e "insert into  targetadds_cart_removal VALUES(4, 1, 1, sku)"
bin/mysql -e "insert into  targetadds_cart_removal VALUES(1, 1, 1, sku)"
bin/mysql -e "insert into  targetadds_cart_removal VALUES(2, 1, 1, sku)"
bin/mysql -e "insert into  targetadds_cart_removal VALUES(3, 1, 1, sku)"

