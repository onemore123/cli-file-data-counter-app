# Get the sum from the files

### TLTR:
Symfony command that goes through the directory structure, finds all the files with specific name and returns the sum

### REQUIREMENTS:

* Git
* Docker
* Docker-compose
* Favourite IDE/code editor

### SETUP

##### Step 1
Clone the repository to your projects folder:
```
$ git clone https://github.com/onemore123/cli-file-data-counter-app.git
```

Change the directory:
```
$ cd ./cli-file-data-counter-app
```

##### Step 2
Build and start docker containers:
```
$ docker-compose up -d --build --remove-orphans
```
Use the link below to get more information on the command:
https://docs.docker.com/compose/reference/up/

##### Step 3
Install symfony packages
```
$ docker exec -it -w /var/www web composer install
```

### COMMAND
Here, all commands are executed from the host. Use *docker exec -it -w /var/www web /bin/bash* to get into container. 
##### Help
To get the command details, use the *--help* option
```
$ docker exec -it -w /var/www web php bin/console app:data-counter:count --help
```

##### Get files data sum
```
$ docker exec -it -w php bin/console app:data-counter:count /var/www/testFolder --file_name=count.txt
```
**Where:**
* **/var/www/testFolder** - required full path to the file directory
* **--file_name=count.txt** - optional file name (*default:* count.txt)

##### Note
There is a **"testFolder"** dummy data directory in the project. 