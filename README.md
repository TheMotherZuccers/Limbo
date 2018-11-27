To get elasticsearch working on your machine


THESE COMMANDS ARE FOR DEBUGGING, NOT PROD
```
docker pull docker.elastic.co/elasticsearch/elasticsearch:6.5.1
docker run -p 9200:9200 -p 9300:9300 -d --name=elasticsearch -e "discovery.type=single-node" docker.elastic.co/elasticsearch/elasticsearch:6.5.1
```

If you have Devilbox also do
```
docker network connect devilbox_app_net elasticsearch
```

Edit your .env, add
```
SEARCH_ENABLED=true
SEARCH_HOSTS=elasticsearch:9200
```

Finally
```
php artisan config:cache
php artisan search:reindex
```

See if it's working with
```
curl 'elasticsearch:9200'
```

See your items with
```
curl 'elasticsearch:9200/items/_search?q=*'
```