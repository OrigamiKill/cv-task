# Projects Docker Setup

If you are unfamiliar with Docker or want to test you installation, instructions can be found on: https://docs.docker.com/get-started/


## Install Docker

Download and install Docker CE from: https://store.docker.com/

For full documentation, visit https://docs.docker.com/reference/

## Install Docker Compose (Linux systems only)

Compose is a tool for defining and running multi-container Docker applications.
On desktop systems like Docker for Mac and Windows, Docker Compose is included as part of those desktop installs.

On Linux systems, install as per the instructions on: https://docs.docker.com/compose/install/


## Hosts file setup

* On Linux, add those lines to the /etc/hosts file:
```
127.0.0.1   cv-task.home
```


### Build the projects

To create Docker images to use within your Docker container, run the following from the *cv-task* project folder:
```bash
docker-compose build
```
Go into php container and execute:
```bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### Run the projects

Attach the project images to a Docker container using:

```bash
docker-compose up -d
```

### Notes

1) Send get request to '/authorize?email=userEmail&password=userPassword' and get your apiKey.