create table products
(
    id    serial primary key,
    title varchar(255)   null,
    price decimal(10, 2) null
);

INSERT INTO products (id, title, price) VALUES (1, 'Три товарища', 49.80);
INSERT INTO products (id, title, price) VALUES (2, 'Триумфальная арка', 349.00);
INSERT INTO products (id, title, price) VALUES (3, 'Один год жизни', 149.00);
INSERT INTO products (id, title, price) VALUES (4, 'Северный дракон', 249.00);

create table users
(
    id         serial primary key,
    first_name  varchar(255) null,
    second_name varchar(255) null,
    birthday    date         null,
    created_at  timestamp    null
);

INSERT INTO users (id, first_name, second_name, birthday, created_at) VALUES (1, 'Петр', 'Петров', '2000-04-14', '2024-02-01 18:17:16');
INSERT INTO users (id, first_name, second_name, birthday, created_at) VALUES (2, 'Иван', 'Иванов', '1997-06-17', '2024-02-02 18:17:43');

create table user_order
(
    user_id    int                                 not null,
    product_id int                                 not null,
    created_at timestamp default CURRENT_TIMESTAMP not null
);

CREATE OR REPLACE FUNCTION update_changetimestamp_column()
RETURNS TRIGGER AS $$
BEGIN
   NEW.created_at = now(); 
   RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_user_order_changetimestamp BEFORE UPDATE
ON user_order FOR EACH ROW EXECUTE PROCEDURE 
update_changetimestamp_column();

INSERT INTO user_order (user_id, product_id, created_at) VALUES (1, 4, '2024-02-14 18:41:25');
INSERT INTO user_order (user_id, product_id, created_at) VALUES (2, 1, '2024-02-14 18:40:52');
INSERT INTO user_order (user_id, product_id, created_at) VALUES (2, 2, '2024-02-14 18:40:51');
INSERT INTO user_order (user_id, product_id, created_at) VALUES (2, 3, '2024-02-02 18:40:45');
