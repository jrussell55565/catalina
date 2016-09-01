INSERT into modules (module_name,file_name,parent_id, priority,access_key, can_be_default,module_icon)
VALUES ('Literature', null, 0 , 40, 'lit',0, 'icon-book');   

INSERT into modules (module_name,file_name,parent_id, priority,access_key, can_be_default,module_icon)
VALUES ('Literature list', 'lit_list', 51 , 5, 'lit_list',1, null);   

INSERT into roles_rights (role_id,module_id) VALUES(1,51);

INSERT into roles_rights (role_id,module_id) VALUES(1,52);

INSERT into modules (module_name,file_name,parent_id, priority,access_key, can_be_default,module_icon)
VALUES ('Create literature', 'create_lit', 51 , 10, 'lit_list',0, null);   

INSERT into roles_rights (role_id,module_id) VALUES(1,53);

ALTER table d_txs ADD column tbl_id int(11);