create table aksessuare
(
	id int not null auto_increment
		primary key,
	name varchar(255) null
)
;

create table category
(
	id_category int not null auto_increment,
	category varchar(255) null,
	console_name_id_name int not null,
	primary key (id_category, console_name_id_name)
)
;

create index fk_category_console_name_idx
	on category (console_name_id_name)
;

create table console
(
	id int not null auto_increment
		primary key,
	name varchar(255) not null,
	is_active tinyint(1) null,
	code int null
)
;

create table console_accessory
(
	console_id int not null,
	accessory_id int not null,
	primary key (console_id, accessory_id),
	constraint console_ accessory_ref
		foreign key (console_id) references gameshop.console (id),
	constraint accessory_console_ref
		foreign key (accessory_id) references gameshop.aksessuare (id)
)
;

create index accessory_console_ref
	on console_accessory (accessory_id)
;

create index console_id
	on console_accessory (console_id)
;

create table console_name
(
	id_name int not null auto_increment
		primary key,
	name varchar(45) null
)
;

alter table category
	add constraint fk_category_console_name
		foreign key (console_name_id_name) references gameshop.console_name (id_name)
;

create table console_specification
(
	console_id int not null,
	specification_id int not null,
	value varchar(255) null,
	primary key (console_id, specification_id),
	constraint console_specification_ref
		foreign key (console_id) references gameshop.console (id)
)
;

create index console_idx
	on console_specification (console_id)
;

create index specification_idx
	on console_specification (specification_id)
;

create table page
(
	id int(10) unsigned not null auto_increment
		primary key,
	code varchar(255) not null,
	title varchar(255) not null,
	parent_id int(10) unsigned null,
	content longtext null,
	keywords text null,
	is_active tinyint(3) unsigned default '1' not null,
	position int default '0' not null,
	constraint page_parent_ref
		foreign key (parent_id) references gameshop.page (id)
)
;

create index page_parent_ref
	on page (parent_id)
;

create table properties
(
	id_properties int not null auto_increment,
	properties varchar(255) null,
	category_id_category int not null,
	category_console_name_id_name int not null,
	primary key (id_properties, category_id_category, category_console_name_id_name),
	constraint fk_properties_category1
		foreign key (category_id_category, category_console_name_id_name) references gameshop.category (id_category, console_name_id_name)
)
;

create index fk_properties_category1_idx
	on properties (category_id_category, category_console_name_id_name)
;

create table specification
(
	id int not null auto_increment
		primary key,
	name varchar(255) null
)
;

alter table console_specification
	add constraint specification_console_ref
		foreign key (specification_id) references gameshop.specification (id)
;

create table user
(
	id int(10) unsigned not null auto_increment
		primary key,
	email varchar(255) not null,
	name varchar(255) not null,
	password varchar(32) null,
	is_active tinyint(3) unsigned default '1' not null
)
;

