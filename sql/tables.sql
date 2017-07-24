create table option_values
(
	id int auto_increment
		primary key,
	option_id int null,
	value varchar(50) null,
	constraint option_values_id_uindex
		unique (id)
)
;

create table product
(
	id bigint auto_increment
		primary key,
	model varchar(50) not null,
	price decimal(22,2) not null,
	constraint product_id_uindex
		unique (id)
)
;

create table product_options
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	unit varchar(10) null,
	constraint product_options_id_uindex
		unique (id),
	constraint product_options_name_uindex
		unique (name)
)
;

create table product_options_values
(
	id int auto_increment
		primary key,
	product_id int not null,
	option_id int not null,
	value_id int null,
	constraint product_options_values_id_uindex
		unique (id)
)
;

