## COD4x Player Screenshots via curl request

#### Config setup
update app/s1.php line no 7 - 9

if you have multiple servers create a copy of s1.php as s2.php, s3.php or any

#### Build
```
docker compose build
```
#### RUN
```
curl -i http://localhost/s1.php
```