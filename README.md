# Mysql to Clickhouse Bridge

### Aplikacja umożliwiająca kopiowanie danych pomiędzy baza mysql oraz clickhouse.

#### Instalacja
    docker-compose build
#### Uruchomienie aplikacji
    docker-compose up -d
    
##### Aplikacja powinna być dostępna pod adresem http://localhost

#### Wejście do kontenera
    docker-compose exec <nazwa kontenera> bash
    np docker-compose exec php-fpm bash