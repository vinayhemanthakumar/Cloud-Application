#!/bin/bash

if [ $# -ne 2 ]
then
echo "Requires 2 bucket names raw-vin and raw-hem to be passed as parameters to run this script"
exit 0
else
echo "Creating DB instance"
aws rds create-db-instance --db-instance-identifier vinaydb --db-instance-class db.t1.micro --engine MySQL --master-username kbryant --master-user-password arizzo44 --allocated-storage 20 --db-name school
aws rds wait db-instance-available --db-instance-identifier vinaydb
echo "DB instance created"

aws rds create-db-instance-read-replica --db-instance-identifier vinaydb-readreplica --source-db-instance-identifier vinaydb

echo "Creating SNS Topic"
aws sns create-topic --name vh_worldseries
echo "Creating SQS Queue"
aws sqs create-queue --queue-name vh_cubs
echo "Creating a Bucket"
aws s3 mb s3://$1 --region us-west-2 
aws s3 mb s3://$2 --region us-west-2 
fi
