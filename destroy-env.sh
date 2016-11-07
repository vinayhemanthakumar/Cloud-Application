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
