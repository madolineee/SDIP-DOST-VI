<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;
use App\Models\StakeholderModel;
use App\Models\PersonModel;
use App\Models\ContactDetailsModel;
use App\Models\StakeholderMembersModel;



class InstitutionController extends BaseController
{

    // home
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('institutions');
        $builder->select('
        institutions.id, institutions.image, institutions.type, 
        stakeholders.name, stakeholders.abbreviation, 
        stakeholders.street, stakeholders.barangay, 
        stakeholders.municipality, stakeholders.province
    ');
        $builder->join('stakeholders', 'stakeholders.id = institutions.stakeholder_id');
        $data['institutions'] = $builder->get()->getResultArray();

        return view('institution/home', $data);
    }


    public function create_institution()
    {
        return view('institution/create');
    }

    public function storeInstitution()
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        $stakeholderData = [
            'name' => $this->request->getPost('name'),
            'street' => $this->request->getPost('street'),
            'barangay' => $this->request->getPost('barangay'),
            'municipality' => $this->request->getPost('municipality'),
            'province' => $this->request->getPost('province'),
            'country' => $this->request->getPost('country'),
            'abbreviation' => $this->request->getPost('abbreviation'),
            'category' => 'Academe',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];
        $db->table('stakeholders')->insert($stakeholderData);
        $stakeholderId = $db->insertID();


        $personData = [
            'honorifics' => $this->request->getPost('honorifics'),
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'designation' => $this->request->getPost('designation'),
            'updated_at' => $timestamp
        ];

        $db->table('persons')->insert($personData);
        $personId = $db->insertID();

        $image = $this->request->getFile('image');

        if (!$image) {
            die("File upload is missing! Check input name and form enctype.");
        }

        if ($image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('uploads/institutions', $newName);
            $imagePath = 'uploads/institutions/' . $newName;
        } else {
            $imagePath = null; // Default to NULL if no image was uploaded
        }

        // Insert into Institutions
        $institutionData = [
            'type' => $this->request->getPost('type'),
            'stakeholder_id' => $stakeholderId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'image' => $imagePath, // Store file path
        ];
        $db->table('institutions')->insert($institutionData);

        $db->table('stakeholder_members')->insert([
            'stakeholder_id' => $stakeholderId,
            'person_id' => $personId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        $contactData = [
            'person_id' => $personId,
            'telephone_num' => $this->request->getPost('telephone_num'),
            'email_address' => $this->request->getPost('email_address'),
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];
        $db->table('contact_details')->insert($contactData);

        return redirect()->to('/institution/home')->with('success', 'Institution added successfully!');
    }
    public function edit($id)
    {
        $db = \Config\Database::connect();

        $institution = $db->table('institutions as i')
            ->select('i.id as institution_id, i.type, i.image, 
                  s.id as stakeholder_id, s.name, s.abbreviation, 
                  s.street, s.barangay, s.municipality, s.province, s.country,
                  p.id as person_id, p.honorifics, p.first_name, p.middle_name, p.last_name, p.designation,
                  c.id as contact_id, c.telephone_num, c.email_address')
            ->join('stakeholders as s', 's.id = i.stakeholder_id', 'left')
            ->join('stakeholder_members as sm', 'sm.stakeholder_id = s.id', 'left') // Correct join
            ->join('persons as p', 'p.id = sm.person_id', 'left') // Now correctly linking persons table
            ->join('contact_details as c', 'c.person_id = p.id', 'left')
            ->where('i.id', $id)
            ->get()
            ->getRowArray();

        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found.');
        }

        return view('institution/edit', ['institution' => $institution]);
    }


    public function update($id)
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        $institution = $db->table('institutions')->where('id', $id)->get()->getRowArray();

        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found!');
        }

        $db->table('stakeholders')->where('id', $institution['stakeholder_id'])->update([
            'name' => $this->request->getPost('name'),
            'abbreviation' => $this->request->getPost('abbreviation'),
            'street' => $this->request->getPost('street'),
            'barangay' => $this->request->getPost('barangay'),
            'municipality' => $this->request->getPost('municipality'),
            'province' => $this->request->getPost('province'),
            'country' => $this->request->getPost('country'),
            'category' => 'Academe',
            'updated_at' => $timestamp
        ]);

        $db->table('persons')->where('id', $institution['id'])->update([
            'honorifics' => $this->request->getPost('honorifics'),
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'designation' => $this->request->getPost('designation'),
            'updated_at' => $timestamp
        ]);

        $db->table('contact_details')->where('person_id', $institution['id'])->update([
            'telephone_num' => $this->request->getPost('telephone_num'),
            'email_address' => $this->request->getPost('email_address'),
            'updated_at' => $timestamp
        ]);

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('uploads/institutions', $newName);
            $imagePath = 'uploads/institutions/' . $newName;

            $db->table('institutions')->where('id', $id)->update(['image' => $imagePath]);
        }

        $db->table('institutions')->where('id', $id)->update([
            'type' => $this->request->getPost('type'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/institution/home')->with('success', 'Institution updated successfully!');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        if (!is_numeric($id)) {
            return redirect()->to('/institution/home')->with('error', 'Invalid Institution ID');
        }

        $institution = $db->table('institutions')->where('id', $id)->get()->getRowArray();

        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found!');
        }

        if (!empty($institution['image']) && file_exists($institution['image'])) {
            unlink($institution['image']);
        }

        $db->table('contact_details')->where('person_id', $institution['id'])->delete();
        $db->table('persons')->where('id', $institution['id'])->delete();
        $db->table('stakeholders')->where('id', $institution['stakeholder_id'])->delete();
        $db->table('institutions')->where('id', $id)->delete();

        return redirect()->to('/institution/home')->with('success', 'Institution deleted successfully!');
    }

    public function view($id)
    {
        $db = \Config\Database::connect();

        $institution = $db->table('institutions as i')
            ->select('i.id as institution_id, i.type, i.image, 
                  s.id as stakeholder_id, s.name, s.abbreviation, 
                  s.street, s.barangay, s.municipality, s.province, s.country,
                  p.id as person_id, p.honorifics, p.first_name, p.middle_name, p.last_name, p.designation,
                  c.id as contact_id, c.telephone_num, c.email_address')
            ->join('stakeholders as s', 's.id = i.stakeholder_id', 'left')
            ->join('stakeholder_members as sm', 'sm.stakeholder_id = s.id', 'left')
            ->join('persons as p', 'p.id = sm.person_id', 'left')
            ->join('contact_details as c', 'c.person_id = p.id', 'left')
            ->where('i.id', $id)
            ->get()
            ->getRowArray();

        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found.');
        }

        return view('institution/details', ['institution' => $institution]);
    }

}


// public function balik_scientist()
// {
//     return view('/institution/balik_scientist/index');
// }
// public function consortium()
// {
//     return view('/institution/consortium/index');
// }
// public function ncrp_members()
// {
//     return view('/institution/ncrp_members/index');
// }
// public function projects()
// {
//     return view('/institution/projects/index');
// }
// public function research_centers()
// {
//     return view('/institution/research_centers/index');
// }
