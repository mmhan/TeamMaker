<?php
class TeamsController extends AppController {

	var $name = 'Teams';

	function admin_index() {
		$this->Team->recursive = 0;
		$this->set('teams', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid team', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('team', $this->Team->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Team->create();
			if ($this->Team->save($this->data)) {
				$this->Session->setFlash(__('The team has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please, try again.', true));
			}
		}
		$projects = $this->Team->Project->find('list');
		$members = $this->Team->Member->find('list');
		$this->set(compact('projects', 'members'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid team', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Team->save($this->data)) {
				$this->Session->setFlash(__('The team has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Team->read(null, $id);
		}
		$projects = $this->Team->Project->find('list');
		$members = $this->Team->Member->find('list');
		$this->set(compact('projects', 'members'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for team', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Team->delete($id)) {
			$this->Session->setFlash(__('Team deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Team was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>