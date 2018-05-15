<?php

use yii\db\Migration;

class m171118_192956_becomeResearcher extends Migration
{
    public function up()
    {
        $sql = "
create table researcher (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  status TINYINT not null DEFAULT 0 COMMENT '0=email not verified',
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  contact_phone varchar(255) NOT NULL,
  contact_skype varchar(255),
  contact_whats_app  varchar(255),
  state_id int(11) UNSIGNED,
  district_id int(11) UNSIGNED comment 'zila == district',
  district_sub_id int(11) UNSIGNED comment 'Pargana/Tehsil == district_sub' ,
  city_id int(11) UNSIGNED,
  custom_location varchar(255),
  address varchar(255),
  email_paypal varchar(255),
  linked_in varchar(255),
  business varchar(255) comment 'Do you own your own genealogy business? If so, what is the name of your business.',
  website varchar(255),
  service tinyint not null DEFAULT 1,
  extra_1 varchar(255),
  extra_2 varchar(255),
  extra_3 varchar(255),
  extra_4 varchar(255),
  extra_5 varchar(255),
  extra_6 varchar(255),
  extra_7 varchar(255),
  extra_8 varchar(255),
  extra_9 varchar(255),
  extra_10 varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Become Researcher';

ALTER TABLE researcher ADD CONSTRAINT researcher_state_id_fk FOREIGN KEY (state_id) REFERENCES state (id);
ALTER TABLE researcher ADD CONSTRAINT researcher_district_id_fk FOREIGN KEY (district_id) REFERENCES district (id);
ALTER TABLE researcher ADD CONSTRAINT researcher_district_sub_id_fk FOREIGN KEY (district_sub_id) REFERENCES district_sub (id);
ALTER TABLE researcher ADD CONSTRAINT researcher_city_id_fk FOREIGN KEY (city_id) REFERENCES city (id);
";
        Yii::$app->db->createCommand($sql)->execute();
    }

    public function down()
    {
        echo "m171118_192956_becomeResearcher cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
