#!/bin/bash

<<<<<<< HEAD
if [ $# -ne 5 ]
then
echo "Requires 5 parameters to be passed to run this script"
exit 0
else
echo "Creating 3 new instances"
aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type t2.micro --user-data file://installapp.sh --count $5 --placement AvailabilityZone=us-west-2a
=======
if [ $# -ne 1 ]
then
echo "Requires the parameter \"image-id\" to be passed to run this script"
else
echo "Creating 3 new instances"
aws ec2 run-instances --image-id $1 --key-name vinay --security-group-ids sg-04fc367d --instance-type t2.micro --user-data file://installapp.sh --count 3 --placement AvailabilityZone=us-west-2a
>>>>>>> 5fdfc6ebf47dcf036f0fba6890ef6ae68374f6d3
echo "New Instances created"

echo "Before Wait"
instance_id=$(aws ec2 describe-instances --query 'Reservations[].Instances[].[InstanceId]' --filters Name=instance-state-name,Values=pending)
echo $instance_id
aws ec2 wait instance-running --instance-ids $instance_id
echo "After wait"

echo "Before the Creation of Load Balancer"
aws elb create-load-balancer --load-balancer-name itmo-544-vh --listeners Protocol=Http,LoadBalancerPort=80,InstanceProtocol=Http,InstancePort=80 --subnets subnet-58436a3c
echo "After the Creation of Load Balancer"

echo "Register the instances created with the load balancer"
aws elb register-instances-with-load-balancer --load-balancer-name itmo-544-vh --instances $instance_id
echo "After registering the instance with load balancer"

echo "Creating Autoscaling Launch Configuration"
<<<<<<< HEAD
aws autoscaling create-launch-configuration --launch-configuration-name $4 --image-id $1 --key-name $2 --instance-type t2.micro --user-data file://installapp.sh
echo "After creating the Autoscaling Launch Configuration"

echo "Creating Autoscaling Group"
aws autoscaling create-auto-scaling-group --auto-scaling-group-name imageserverdemo --launch-configuration $4 --availability-zone us-west-2a --max-size 5 --min-size 0 --desired-capacity 1
=======
aws autoscaling create-launch-configuration --launch-configuration-name imageserver --image-id $1 --key-name vinay --instance-type t2.micro --user-data file://installapp.sh
echo "After creating the Autoscaling Launch Configuration"

echo "Creating Autoscaling Group"
aws autoscaling create-auto-scaling-group --auto-scaling-group-name imageserverdemo --launch-configuration imageserver --availability-zone us-west-2a --max-size 5 --min-size 0 --desired-capacity 1
>>>>>>> 5fdfc6ebf47dcf036f0fba6890ef6ae68374f6d3
echo "After creating AutoScaling Group Created"

echo "Attaching created instances to auto scaling group"
aws autoscaling attach-instances --instance-ids $instance_id --auto-scaling-group-name imageserverdemo
echo "After attaching Instances to auto-scaling-group"

echo "Attaching load balancer to auto scaling group"
aws autoscaling attach-load-balancers --auto-scaling-group-name imageserverdemo --load-balancer-names itmo-544-vh
echo "After attaching the load balancer to auto-scaling-group"
<<<<<<< HEAD
echo "Successful created instances"
=======
>>>>>>> 5fdfc6ebf47dcf036f0fba6890ef6ae68374f6d3
fi
