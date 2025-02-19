<?php

namespace App\Models;

use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Models\GroupModel as ShieldGroupModel;

class GroupModel extends ShieldGroupModel
{
    /**
     * @var string
     */
    protected $returnType = Group::class;
}