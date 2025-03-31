<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class NrcpController extends BaseController
{
    public function nrcp_members()
    {

        $db = \Config\Database::connect();
        $builder = $db->table('nrcp_members nrcp');
        $builder->select('
            nrcp.id, 
            nrcp.description, 
            nrcp.image, 
            i.id as institution_id, 
            s.name as institution_name, 
            p.id as person_id, 
            p.honorifics, 
            p.first_name, 
            p.middle_name, 
            p.last_name, 
            p.role
        ');
        $builder->join('institutions i', 'i.id = nrcp.institution_id', 'left');
        $builder->join('stakeholders s', 's.id = i.stakeholder_id', 'left');
        $builder->join('persons p', 'p.id = nrcp.person_id', 'left');

        $data['nrcp_members'] = $builder->get()->getResultArray();
        return view('/institution/nrcp_members/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        // Fetch institutions from stakeholders via institutions table
        $institutions = $db->table('institutions i')
            ->select('i.id, s.name')
            ->join('stakeholders s', 's.id = i.stakeholder_id', 'left')
            ->get()
            ->getResult();

        return view('institution/nrcp_members/create', [
            'institutions' => $institutions
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        // Insert new person record
        $personData = [
            'honorifics' => $this->request->getPost('honorifics'),
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        $db->table('persons')->insert($personData);
        $person_id = $db->insertID(); // Get the last inserted person_id

        // Handle file upload for image
        $imageFile = $this->request->getFile('image');
        $imagePath = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move('uploads/balik_scientists', $newName);
            $imagePath = 'uploads/balik_scientists/' . $newName;
        }

        // Get selected memberships
        $selectedMemberships = $this->request->getPost('dynamic_choice');

        if (!empty($selectedMemberships)) {
            foreach ($selectedMemberships as $membership) {
                $institutionField = "institution_" . str_replace(' ', '_', $membership); // Matching input name
                $institutionId = $this->request->getPost($institutionField);

                if ($membership === "Balik Scientist") {
                    $scientistData = [
                        'institution_id' => $institutionId,
                        'person_id' => $person_id,
                        'description' => $this->request->getPost('description'),
                        'image' => $imagePath,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                    $db->table('balik_scientist_engaged')->insert($scientistData);
                } elseif ($membership === "NRCP") {
                    $ncrpData = [
                        'institution_id' => $institutionId,
                        'person_id' => $person_id,
                        'description' => $this->request->getPost('description'),
                        'image' => $imagePath,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                    $db->table('nrcp_members')->insert($ncrpData); // Assuming you have a table for NCRP members
                }
            }
        }

        return redirect()->to('/institution/nrcp_members/index')->with('success', 'Scientist added successfully!');
    }
}
