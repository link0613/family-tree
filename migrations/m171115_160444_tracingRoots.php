<?php

use yii\db\Migration;

class m171115_160444_tracingRoots extends Migration
{
    public function up() {
//	    $this->profession();
//	    $this->caste_name();
//	    $this->state__district__district_sub__city();
//	    $this->stateInsert();
	    $this->tracingRoots();
    }
	private function tracingRoots() {
        $sql = "
CREATE TABLE `tr_applicant` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  status TINYINT not null DEFAULT 0 COMMENT '0=email not verified',
  name varchar(255) NOT NULL,
  contact_address varchar(255) NOT NULL,
  contact_phone varchar(255) NOT NULL,
  contact_fax varchar(255),
  contact_email varchar(255) NOT NULL,
  profession_id int(11) UNSIGNED,
  father varchar(255),
  mother varchar(255),
  grandfather varchar(255),
  grandmother varchar(255),
  great_grandfather varchar(255),
  great_grandmother varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Tracing roots applicant';
ALTER TABLE tr_applicant ADD CONSTRAINT tr_applicant_profession_id_fk FOREIGN KEY (profession_id) REFERENCES profession (id);

CREATE TABLE `tr_ancestor` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  applicant_id int(11) UNSIGNED NOT NULL,
  name varchar(255),
  migrate_year SMALLINT,
  migrate_age TINYINT,
  caste_name_id int(11) UNSIGNED,
  profession_id int(11) UNSIGNED,
  state_id int(11) UNSIGNED,
  district_id int(11) UNSIGNED comment 'zila == district',
  district_sub_id int(11) UNSIGNED comment 'Pargana/Tehsil == district_sub' ,
  city_id int(11) UNSIGNED,
  police_station varchar(255),
  post_office varchar(255),
  cities_neighbor varchar(255) comment 'json: list of city id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Tracing roots ancestor';
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_tr_applicant_id_fk FOREIGN KEY (applicant_id) REFERENCES tr_applicant (id) ON DELETE CASCADE ;
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_profession_id_fk FOREIGN KEY (profession_id) REFERENCES profession (id);
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_caste_name_id_fk FOREIGN KEY (caste_name_id) REFERENCES caste_name (id);
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_state_id_fk FOREIGN KEY (state_id) REFERENCES state (id);
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_district_id_fk FOREIGN KEY (district_id) REFERENCES district (id);
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_district_sub_id_fk FOREIGN KEY (district_sub_id) REFERENCES district_sub (id);
ALTER TABLE tr_ancestor ADD CONSTRAINT tr_ancestor_city_id_fk FOREIGN KEY (city_id) REFERENCES city (id);

CREATE TABLE `tr_ancestor_details` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY,
  property TEXT comment 'Details of property owned by the migrant',
  family_members TEXT comment 'json: Name of the Parents and immediate family members of the Migrant with nick names, if any',
  correspondence TEXT comment 'Do you have copy of correspondence exchanged by the Migrant with his relations or others in India? If yes – give details',
  stories TEXT comment 'Has the applicant heard any stories or details with regard to his Ancestors who migrated from India? If yes, give details',
  other TEXT comment 'Additional information/documents that could be helpful in tracing the roots could also
be submitted along with the application.'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Tracing roots ancestor details';
ALTER TABLE tr_ancestor_details ADD CONSTRAINT tr_ancestor_details_tr_ancestor_id_fk FOREIGN KEY (id) REFERENCES tr_ancestor (id) ON DELETE CASCADE ;

CREATE TABLE `tr_ancestor_migration` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY,
  ship varchar(255) comment 'Name of the ship',
  agent varchar(255) comment 'Particulars of the Agent',
  port_embarkation varchar(255) comment 'Port of embarkation',
  date_embarkation DATE comment 'Date of embarkation',
  port_disembarkation varchar(255) comment 'Port of disembarkation',
  date_disembarkation DATE comment 'Date when reached destination'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Details of Migration';
ALTER TABLE tr_ancestor_migration ADD CONSTRAINT tr_ancestor_migration_tr_ancestor_id_fk FOREIGN KEY (id) REFERENCES tr_ancestor (id) ON DELETE CASCADE ;

      ";

		Yii::$app->db->createCommand($sql)->execute();

	}

	private function profession() {
		$sql = "
CREATE TABLE `profession` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO profession (name) VALUES
('Professor'),
('Teacher'),
('Actor'),
('Clerg'),
('Musician'),
('Philosopher'),
('Visual Artist'),
('Writer'),
('Audiologist'),
('Chiropractor'),
('Dentist'),
('Dietitian'),
('Doctor'),
('Medical Laboratory Scientist'),
('Midwive'),
('Nurse'),
('Occupational therapist'),
('Optometrist'),
('Pathologist'),
('Pharmacist'),
('Physical therapist'),
('Physician'),
('Psychologist'),
('Speech-language pathologist'),
('Accountant'),
('Actuarie'),
('Agriculturist'),
('Architect'),
('Economist'),
('Engineer'),
('Interpreter'),
('Attorney at la'),
('Advocate'),
('Solicitor'),
('Librarian'),
('Statistician'),
('Surveyor'),
('Urban planner'),
('Human resource'),
('Firefighter'),
('Judge'),
('Military officer'),
('Police officer'),
('Air traffic controller'),
('Aircraft pilot'),
('Sea captain'),
('Scientist'),
('Astronomer'),
('Biologist'),
('Botanist'),
('Ecologist'),
('Geneticist'),
('Immunologist'),
('Pharmacologist'),
('Virologist'),
('Zoologist'),
('Chemist'),
('Geologist'),
('Meteorologist'),
('Oceanographer'),
('Physicist'),
('Programmer'),
('Web developer'),
('Designer'),
('Graphic designer'),
('Web designer')
;
";
		Yii::$app->db->createCommand($sql)->execute();
	}

	private function caste_name() {
		$sql = "
CREATE TABLE `caste_name` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO caste_name (name) VALUES
('Adaviyar'),
('Agri caste'),
('Ahir'),
('Ahirwar'),
('Anjana Chaudhari'),
('Anuppan'),
('Aradhya'),
('Arayan'),
('Arora'),
('Asati'),
('Awadhiya (caste)'),
('Badhiphul'),
('Bahti'),
('Bakho'),
('Balija'),
('Banai (sub-tribe)'),
('Bangar (caste)'),
('Bari (caste)'),
('Bhada (Charan)'),
('Bhalay'),
('Bhambi'),
('Bhandari caste'),
('Bhanushali'),
('Bharbhunja (Hindu)'),
('Bhat'),
('Bhatraju'),
('Bhavsar'),
('Bhil Gametia'),
('Bhil Mama'),
('Bhishti'),
('Bhope'),
('Bhulia'),
('Chakyar'),
('Chalavadi'),
('Chamail'),
('Chasa caste'),
('Chaukalshi'),
('Chhaparband'),
('Chhipi'),
('Chik (social group)'),
('Chobdar'),
('Chunara'),
('Churihar'),
('Dahima'),
('Daivadnya Brahmin'),
('Dangi'),
('Dashora'),
('Deshastha Brahmin'),
('Desikar'),
('Devadiga'),
('Devanga'),
('Dhadhor'),
('Dheevara (caste)'),
('Dhekaru'),
('Dhusia'),
('Dubla'),
('Elur Chetty'),
('Eradi'),
('Ezhava'),
('Gadaria'),
('Gadhia community'),
('Gahoi'),
('Gamalla'),
('Ganak'),
('Gandhabanik'),
('Ganiga'),
('Gauda and Kunbi'),
('Gawaria'),
('Ghamaila'),
('Ghasiara'),
('Ghirth'),
('Gihara'),
('Godha'),
('Gollewar'),
('Goriya'),
('Gowari'),
('Gurjar'),
('Halba people'),
('Hatkar'),
('Hazaris'),
('Heri (caste)'),
('Hindu Burud Caste'),
('Holar caste'),
('Hoogar'),
('Hurkiya'),
('Idangai'),
('Ilayathu'),
('Istimrari'),
('Iyer'),
('Jalia Kaibarta'),
('Jāti'),
('Jhamar caste'),
('Jogi'),
('Jogi Faqir'),
('Joisar'),
('Kadava Patidar'),
('Kadia (Muslim)'),
('Kakur'),
('Kalbi'),
('Kalingi'),
('Kalwar (caste)'),
('Kamma (caste)'),
('Kammalan'),
('Kanakkan'),
('Kanet'),
('Kaniyar'),
('Kannadiya Naidu'),
('Kansara'),
('Karmakar'),
('Karmani'),
('Kartha'),
('Kashmiri Muslim tribes from Hindu lineage'),
('Kasuadhan'),
('Kathi Darbar'),
('Keminje'),
('Kesarvani'),
('Kewat'),
('Khandal'),
('Khant (caste)'),
('Kharol'),
('Kharwa caste'),
('Khateek'),
('Khati'),
('Khatri'),
('Kirar'),
('Komar (caste)'),
('Komati caste'),
('Konar (caste)'),
('Koshta'),
('Kshatriya'),
('Kuchband'),
('Kulala'),
('Kumawat'),
('Kumbara Gowda'),
('Kunbi'),
('Kuravar'),
('Kurup'),
('Kuta (caste)'),
('Kuthaliya Bora'),
('Labbay'),
('Lakhera'),
('Lavana'),
('Leva Patel'),
('List of gotras'),
('List of Telugu castes'),
('Lodha'),
('Lonari'),
('Madiga'),
('Mahishya'),
('Mahton'),
('Mahuri'),
('Maiya'),
('Mal (caste)'),
('Mala (caste)'),
('Mali caste'),
('Mallav Samaj'),
('Mang (caste)'),
('Manipuri Brahmin'),
('Mannadiyar'),
('Maratha'),
('Maulika Kayastha'),
('Meshuchrarim'),
('Mirshikar'),
('Mukkulathor'),
('Mukkuvar'),
('Mundhra'),
('Muslim Dhobi'),
('Nadar (caste)'),
('Nagarathar'),
('Nai (caste)'),
('Nambiar (Ambalavasi caste)'),
('Nambudiri'),
('Narikurava'),
('Nassuvan'),
('Natrayat Rajputs'),
('Nattathi Nadar'),
('Nattukottai Nagarathar'),
('Navnat'),
('Nayak (Hindu)'),
('Nedumpally'),
('Neelagar'),
('Nethakani'),
('Niari'),
('Noongar (caste)'),
('Oswal'),
('Pachhimi'),
('Padhar'),
('Padmashali'),
('Pancha-Dravida'),
('Pancha-Gauda'),
('Panchkalshi'),
('Pannaiyar Caste'),
('Paravar'),
('Patanwadia'),
('Pathare Prabhu'),
('Pathare Prabhu (Kanchole)'),
('Pattamkattiyar'),
('Pattariyar'),
('Patwa'),
('Pindari'),
('Pipa Kshatriya'),
('Purabi'),
('Qalandar (clan)'),
('Qassab'),
('Raju'),
('Ramdasia'),
('Ramoshi'),
('Rastogi'),
('Rathodia'),
('Rathwa'),
('Raut (caste)'),
('Ravana Rajputs'),
('Rawat cast'),
('Rayee'),
('Rayeen (Hindu)'),
('Roniaur'),
('Rosha (subcaste)'),
('Sachora Brahmin'),
('Sadh'),
('Sahmal'),
('Sai Suthar'),
('Saini'),
('Salaat (caste)'),
('Saliya'),
('Salvi (caste)'),
('Samantan'),
('Sambandam'),
('Sansi people'),
('Sapera (Muslim)'),
('Sapera caste'),
('Sathwara'),
('Seervi'),
('Sembadavar'),
('Sengunthar'),
('Shah (caste)'),
('Shankarjati'),
('Shenva'),
('Sidh community'),
('Sikligar'),
('Sinduria (caste)'),
('Sondhia'),
('Soni (caste)'),
('Sorathia'),
('Sunar'),
('Sundhi'),
('Sunwani'),
('Suryavanshi Aare Katika'),
('Swakula Sali'),
('Swarnkar'),
('Tanti'),
('Tattama'),
('Thakor'),
('Thampan'),
('Thandan'),
('Thathagar'),
('Thathera'),
('Thirumulpad'),
('Thogataveera'),
('Tuluva Hebbars'),
('Turaiha'),
('Tyagi'),
('Uppara'),
('Vadde raju'),
('Vaddera'),
('Vaddi'),
('Vaishya Vani'),
('Valan'),
('Valangai'),
('Valiathan'),
('Vankar'),
('Vannar'),
('Vanza'),
('Vanzha'),
('Variar'),
('Velama'),
('Vijayvargiya'),
('Yadav');
        ";
		Yii::$app->db->createCommand($sql)->execute();
	}

	private function state__district__district_sub__city() {
		$sql = "
CREATE TABLE `state` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `district` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  state_id int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX district_state_id_name_uindex ON district (state_id, name);
ALTER TABLE district ADD CONSTRAINT district_state_id_fk FOREIGN KEY (state_id) REFERENCES state (id);

CREATE TABLE `district_sub` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  district_id int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Sub-district it is Pargana/Tehsil';
CREATE UNIQUE INDEX district_sub_district_id_name_uindex ON district_sub (district_id, name);
ALTER TABLE district_sub ADD CONSTRAINT district_sub_district_id_fk FOREIGN KEY (district_id) REFERENCES district (id);

CREATE TABLE `city` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  district_sub_id int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE city ADD CONSTRAINT city_district_sub_id_fk FOREIGN KEY (district_sub_id) REFERENCES district_sub (id);
CREATE UNIQUE INDEX city_district_sub_id_name_uindex ON city (district_sub_id, name);

";
		Yii::$app->db->createCommand($sql)->execute();
	}

	private function stateInsert() {
		$sql = "delete from city;
delete from district_sub;
delete from district;
delete from state;";
		Yii::$app->db->createCommand($sql)->execute();

		ini_set('memory_limit', '2G');
		$aState = require __DIR__.'/171115_160444_tracingRoots/states.php';

		foreach ($aState as $state => $aItems) {
			$sql = "INSERT into `state` (name) VALUES (:v)";
			Yii::$app->db->createCommand($sql, [':v' => $state])->execute();
			$stateID = Yii::$app->db->lastInsertID;

			$aInserted = [
				'dist' => [],
				'distSub' => [],
				'city' => [],
			];
			$aCity = [];
			foreach ($aItems as $item) {
				if (  5!=count($item) ) {
					throw new Exception("\$state='$state'. Error: Wrong data format. \$item=".print_r($item,true));
				}

				$n1 = trim($item[1]);
				$n2 = trim($item[3]);
				if ( !is_numeric($n1) || !is_numeric($n2) ) {
					throw new Exception("\$state='$state'. Error: Wrong data format. \$item=".print_r($item,true));
				}

				if ( 0==(int)$n1 || 0==(int)$n2 )
					continue;
				$district = trim($item[0]);
				$districtSub = trim($item[2]);
				$city = $this->db->quoteValue(trim($item[4]));

				if ( !isset($aInserted['dist'][$district]) ) {
					$aInserted['dist'][$district] = true;
					$sql = "INSERT into `district` (state_id, name) VALUES (:state, :v);";
					Yii::$app->db->createCommand($sql, [':state' => $stateID,':v' => $district])->execute();
					$distID = Yii::$app->db->lastInsertID;
				}
				if ( !isset($aInserted['distSub'][$districtSub]) ) {
					$aInserted['distSub'][$districtSub] = true;
					$sql = "INSERT into `district_sub` (district_id,name) VALUES (:dist,:v)";
					Yii::$app->db->createCommand($sql, [':dist'=>$distID,':v' => $districtSub])->execute();
					$distSubID = Yii::$app->db->lastInsertID;
				}

				$ins = "($distSubID,$city)";
				$k = mb_strtolower($ins, 'utf8');
				if ( !isset($aInserted['city'][$k]) ) {
					$aInserted['city'][$k] = true;
					$aCity[] = $ins;
				}

				if ( count($aCity) > 2000 ) {
					$this->cityInsert($aCity);
					$aCity = [];
				}
			}

			$this->cityInsert($aCity);
		}



	}

	private function cityInsert($aCity) {
		if (empty($aCity))
			return;

		$sql = "INSERT into `city` (district_sub_id,name) VALUES ".join(',',$aCity);
		Yii::$app->db->createCommand($sql)->execute();
	}

    public function down()
    {
	    echo "m171115_160444_tracingRoots cannot be reverted.\n";

        return 1;
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
