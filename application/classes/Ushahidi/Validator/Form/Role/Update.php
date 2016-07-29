<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Ushahidi Form Stage Validator
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

use Ushahidi\Core\Entity;
use Ushahidi\Core\Entity\FormRepository;
use Ushahidi\Core\Entity\RoleRepository;
use Ushahidi\Core\Tool\Validator;

class Ushahidi_Validator_Form_Role_Update extends Validator
{
	protected $form_repo;
	protected $role_repo;
	protected $default_error_source = 'form_role';

	public function setFormRepo(FormRepository $form_repo)
	{
		$this->form_repo = $form_repo;
	}
	
	public function setRoleRepo(RoleRepository $role_repo)
	{
		$this->role_repo = $role_repo;
	}

	protected function getRules()
	{
		return [
			'form_id' => [
				['digit'],
				[[$this->form_repo, 'exists'], [':value']],
			],
			'roles' => [
				[[$this, 'checkRoles'], [':validation',':value']],
			],
		];
	}

	/**
	 * Check roles exist
	 *
	 * @param  Validation $validation
	 * @param  Array      $roles
	 */	
	public function checkRoles(Validation $validation, $roles)
	{
		if (!$roles) {
			return;
		}

		foreach ($roles as $role_id)
		{
			if (! $this->role_repo->idExists($role_id))
			{
				$validation->error('roles', 'rollDoesNotExist', [$role_id]);
			}
		}
	}
}
