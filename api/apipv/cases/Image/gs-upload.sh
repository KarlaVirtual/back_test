#! /bin/bash

# Replace "/path/to/gsutil/" with the path of your gsutil installation.
PATH="$PATH":/home/backend/google-cloud-sdk/bin/gsutil/

# Replace "/home/username/" with the path of your home directory in Linux/Mac.
# The ".boto" file contains the settings that helps you connect to Google Cloud Storage.

# A simple gsutil command that returns a list of files/folders in your bucket.
# Replace "yourbucketname" with a bucket name from your Google Cloud Storage.
# You can replace this line with your own gsutil command to upload a file, etc.
/home/backend/google-cloud-sdk/bin/gsutil -h "Cache-Control:public,max-age=604800" cp /home/home2/backend/images/m/msjT1599850914.png gs://virtualcdnrealbucket/m/
