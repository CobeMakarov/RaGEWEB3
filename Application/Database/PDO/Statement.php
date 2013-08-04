<?php
	/*
		RaGEWEB 2
	*/

	class pdo_database_statement {
		public $error, $field_count, $insert_id, $num_rows, $result, $rows;

		private $statement;

		public function __construct($statement, $insert_id) {
			$this->error = $statement->errorCode();
			$this->field_count = $statement->columnCount();
			$this->insert_id = $insert_id;
			$this->num_rows = $statement->rowCount();

			if ($this->field_count == 1) {
				$this->result = $statement->fetchColumn();
			} else {
				$this->result = '';
			}

			$this->statement = $statement;
		}

		public function to_array() {
			return $this->statement->fetch(PDO::FETCH_ASSOC);
		}
	}
?>