#!/bin/bash

if [ $# -ne 3 ]
then
echo "Requires the 3 parameter values for  \"auto-scaling-group-name\", \"launch-configuration-name\" and \"load-balancer-name\" to be passed to run this script"
else
echo "Retrieve instance id's attached to the auto-scaling-group"
instance_id=$(aws autoscaling describe-auto-scaling-instances --query 'AutoScalingInstances[*].[InstanceId]')
echo "After retrieving Instance ID"

echo "Set desired capacity to 0"
aws autoscaling set-desired-capacity --auto-scaling-group-name $1 --desired-capacity 0
echo "Desired capacity set to 0"

echo "Wait until the instances are terminated"
echo $instance_id
aws ec2 wait instance-terminated --instance-ids $instance_id
echo "Wait is completed and instances in Running state are terminated"

sleep 30

echo "Deleting auto-scaling-group"
aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $1
echo "AutoScaling group-name deleted"

echo "Deleting launch-configuration-group"
aws autoscaling delete-launch-configuration --launch-configuration-name $2
echo "Launch configuration deleted"

echo "Deleting load-balancer"
aws elb delete-load-balancer --load-balancer-name $3
echo "Load-balancer deleted"

#delete-database instance
aws rds delete-db-instance --db-instance-identifier vinaydb-readreplica --skip-final-snapshot
echo "Waiting for database to be terminated"
aws rds wait db-instance-deleted --db-instance-identifier vinaydb-readreplica
aws rds delete-db-instance --db-instance-identifier vinaydb --skip-final-snapshot
aws rds wait db-instance-deleted --db-instance-identifier vinaydb

#delete s3 bucket
aws s3 rb s3://raw-vin --force
aws s3 rb s3://raw-hem --force

#delete sns topic
ARN=`aws sns list-topics --query 'Topics[*]'.'TopicArn' | cut -d\" -f2`
aws sns delete-topic --topic-arn $ARN

#delete sqs queue
URL=`aws sqs get-queue-url --queue-name vh_cubs --query 'QueueUrl' | cut -d\" -f2`
aws sqs delete-queue --queue-url $URL

fi
