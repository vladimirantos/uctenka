create table users(
  email varchar(50) not null primary key,
  name varchar(80) not null unique,
  password varchar(60) not null,
  access enum('admin', 'user') default 'user' not null,
  registrationDate timestamp default current_timestamp not null
);

create table groups(
  idGroup int not null auto_increment primary key,
  owner varchar(50) not null,
  name varchar(50) not null,
  type enum('private', 'shared') not null,
  creationDate timestamp default current_timestamp not null,
  foreign key(owner) references users(email) on delete cascade on update cascade
);

create table group_members(
  idGroupMember int not null auto_increment primary key,
  idGroup int not null,
  user varchar(50) not null,
  current tinyint(1) not null default 1,
  foreign key (idGroup) references groups(idGroup) on delete cascade,
  foreign key(user) references users(email) on delete cascade on update cascade
);

create table payments(
  idPayment int not null auto_increment primary key,
  idGroup int not null,
  user varchar(50) not null,
  description text not null,
  price int not null,
  paymentsDate timestamp default current_timestamp not null,
  foreign key (idGroup) references groups(idGroup) on delete cascade,
  foreign key(user) references users(email) on delete cascade on update cascade
);

create table log(
  id int not null auto_increment primary key,
  sender varchar(50) not null,
  idGroup int not null,
  text text not null,
  creationDate timestamp default current_timestamp not null,
  foreign key (sender) references users(email) on delete cascade on update cascade,
  foreign key(idGroup) references groups(idGroup) on delete CASCADE
);

create trigger registerUser after insert on users for each row
  begin
    insert into groups(owner, name, type) values(NEW.email, "Moje skupina", "private");
  end;

create TRIGGER groupMember after insert on groups for each row
  begin
    insert into group_members(idGroup, user, current) values(NEW.idGroup, NEW.owner, true);
  end;




create view financial_group_info AS
  select g.idGroup, g.name as groupName, type, creationDate, current, coalesce(sum(p.totalPrice),0) as totalPrice,
  g.owner, u.email as userId, u.name as userName
  from financial_group as g
  left join payments as p on p.financialGroup = g.idGroup
  join users_financial_groups as ufg on ufg.financialGroup = g.idGroup
  join users as u on u.email = ufg.user
  GROUP BY g.idGroup, g.name;

create view payments_financial_group AS
  select p.id as idPayment, p.owner, u.name as ownerName, p.totalPrice, p.description, p.purchaseDate, g.idGroup, g.name as groupName, type, creationDate, current,
         coalesce(sum(si.id), 0) as hasItems
  from payments as p
    JOIN financial_group as g on g.idGroup = p.financialGroup
    left join shopping_items as si on si.payment = p.id
    join users as u on u.email = p.owner
  GROUP BY p.id


create table login_log(
  id int AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(50) not null,
  loginDate TIMESTAMP DEFAULT current_timestamp
);