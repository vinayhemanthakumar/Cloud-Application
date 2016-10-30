#!/bin/bash

echo "Creating DB instance"
aws rds create-db-instance --db-instance-identifier vinaydb --db-instance-class db.m1.small --engine MySQL --master-username masterawsuser --master-user-password masteruserpassword --allocated-storage 20 --backup-retention-period 3
aws rds wait db-instance-available --db-instance-identifier vinaydb
echo "DB instance created"

