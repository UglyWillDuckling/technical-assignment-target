#!/usr/bin/env bash

bin/mysql -e "select * FROM domain_events ORDER BY occurred_on"

