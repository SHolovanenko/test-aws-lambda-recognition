## Test case for AWS Lambda, AWS Rekognition, DynamoDB

### Description:
Step function should read an image from the S3 bucket and find dogs via AWS Recognition. If a dog
wasn&#39;t found send an email with the image and a message &quot;dog not found&quot; via SES. If yes write
image name, tags/labels which were gotten from Recognition into DynamoDB.

### Conditions:
- Lambdas should be written on PHP. You can use Bref to speed up development.
- Step function should be developed on the Serverless framework. 
- For AWS development use AWS free tier account.
