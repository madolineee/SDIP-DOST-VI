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

        // Select the required columns
        $builder->select('
        institutions.id, institutions.image, institutions.type, 
        stakeholders.name, stakeholders.abbreviation, 
        stakeholders.street, stakeholders.barangay, 
        stakeholders.municipality, stakeholders.province
    ');

        // Join the stakeholders table
        $builder->join('stakeholders', 'stakeholders.id = institutions.stakeholder_id');

        // Add a condition to filter only active institutions
        $builder->where('institutions.status', 'active');

        // Get the results and pass them to the view
        $data['institutions'] = $builder->get()->getResultArray();

        return view('institution/home', $data);
    }



    public function create_institution()
    {
        $db = \Config\Database::connect();
        $stakeholders = $db->table('stakeholders')
            ->where('category', 'Academe')
            ->get()
            ->getResultArray();

        return view('institution/create', ['stakeholders' => $stakeholders]);
    }

    public function getStakeholderDetails($stakeholderId)
    {
        $db = \Config\Database::connect();

        // Fetch stakeholder data
        $stakeholder = $db->table('stakeholders')
            ->where('id', $stakeholderId)
            ->get()
            ->getRowArray();

        if (!$stakeholder) {
            return $this->response->setJSON([]);
        }

        // Fetch associated person details using stakeholder_members
        $person = $db->table('persons')
            ->select('persons.id as person_id, persons.honorifics, persons.first_name, persons.middle_name, persons.last_name, persons.designation')
            ->join('stakeholder_members', 'stakeholder_members.person_id = persons.id')
            ->where('stakeholder_members.stakeholder_id', $stakeholderId)
            ->get()
            ->getRowArray();

        // If no person data found, return empty response
        if (!$person) {
            return $this->response->setJSON([]);
        }

        // Fetch contact details from contact_details table using the person_id
        $contactDetails = $db->table('contact_details')
            ->select('telephone_num, email_address')
            ->where('person_id', $person['person_id'])  // Using the correct person_id
            ->get()
            ->getRowArray();

        // Merge stakeholder, person, and contact details
        return $this->response->setJSON(array_merge($stakeholder, $person, $contactDetails ?? []));
    }

    public function storeInstitution()
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        $stakeholderId = $this->request->getPost('stakeholder_id');
        
        $image = $this->request->getFile('image');
        $imagePath = null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('uploads/institutions', $newName);
            $imagePath = 'uploads/institutions/' . $newName;
        }

        // Insert into Institutions
        $institutionData = [
            'type' => $this->request->getPost('type'),
            'stakeholder_id' => $stakeholderId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'image' => $imagePath
        ];
        $db->table('institutions')->insert($institutionData);

        return redirect()->to('/institution/home')->with('success', 'Institution added successfully!');
    }


    public function checkInstitutionExists($stakeholder_id)
    {
        // Perform a query directly in the controller to check if the institution exists
        $db = \Config\Database::connect();
        $builder = $db->table('institutions'); // Assuming 'institutions' is your table name
        $builder->where('id', $stakeholder_id);
        $query = $builder->get();

        // Check if the institution was found
        $exists = $query->getNumRows() > 0;

        return $this->response->setJSON(['exists' => $exists]);
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        // Fetch the institution details
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

        return view('institution/edit', ['institution' => $institution]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        // Fetch the institution record
        $institution = $db->table('institutions')->where('id', $id)->get()->getRowArray();
        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found!');
        }

        // Fetch the person_id linked to the stakeholder
        $person = $db->table('stakeholder_members')
            ->select('person_id')
            ->where('stakeholder_id', $institution['stakeholder_id'])
            ->get()
            ->getRowArray();

        if ($person) {
            // Update Person Details
            $db->table('persons')->where('id', $person['person_id'])->update([
                'honorifics' => $this->request->getPost('honorifics'),
                'first_name' => $this->request->getPost('first_name'),
                'middle_name' => $this->request->getPost('middle_name'),
                'last_name' => $this->request->getPost('last_name'),
                'designation' => $this->request->getPost('designation'),
                'updated_at' => $timestamp
            ]);

            // Update Contact Details
            $db->table('contact_details')->where('person_id', $person['person_id'])->update([
                'telephone_num' => $this->request->getPost('telephone_num'),
                'email_address' => $this->request->getPost('email_address'),
                'updated_at' => $timestamp
            ]);
        }

        // Update Stakeholder Details
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

        // Handle Image Update
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image if exists
            $oldImage = $institution['image'];
            if (!empty($oldImage) && file_exists($oldImage)) {
                unlink($oldImage);
            }

            // Upload new image
            $newName = $image->getRandomName();
            $image->move('uploads/institutions', $newName);
            $imagePath = 'uploads/institutions/' . $newName;

            // Update Image Path in Database
            $db->table('institutions')->where('id', $id)->update(['image' => $imagePath]);
        }

        // Update Institution Details
        $db->table('institutions')->where('id', $id)->update([
            'type' => $this->request->getPost('type'),
            'updated_at' => $timestamp
        ]);

        return redirect()->to('/institution/home')->with('success', 'Institution updated successfully!');
    }


    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Check if the ID is numeric
        if (!is_numeric($id)) {
            return redirect()->to('/institution/home')->with('error', 'Invalid Institution ID');
        }

        // Fetch the institution details
        $institution = $db->table('institutions')->where('id', $id)->get()->getRowArray();

        // If the institution is not found
        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found!');
        }

        // Set the status to 'inactive' instead of deleting the record
        $db->table('institutions')->where('id', $id)->update(['status' => 'inactive']);

        // Optionally, you can also set the associated person's status to 'inactive' if needed
        // $db->table('persons')->where('id', $institution['person_id'])->update(['status' => 'inactive']);

        return redirect()->to('/institution/home')->with('success', 'Institution status set to inactive!');
    }

    public function view($id)
    {
        $db = \Config\Database::connect();

        $institution = $db->table('institutions as i')
            ->select('i.id as institution_id, i.type, i.image, 
              s.id as stakeholder_id, s.name, s.abbreviation, 
              s.street, s.barangay, s.municipality, s.province, s.country,
              p.id as person_id, CONCAT(p.honorifics, " ", p.first_name, " ", p.middle_name, " ", p.last_name) as person_name, p.designation,
              c.id as contact_id, c.telephone_num, c.email_address')
            ->join('stakeholders as s', 's.id = i.stakeholder_id', 'left')
            ->join('stakeholder_members as sm', 'sm.stakeholder_id = s.id', 'left')
            ->join('persons as p', 'p.id = sm.person_id', 'left')
            ->join('contact_details as c', 'c.person_id = p.id', 'left')
            ->where('i.id', $id)
            ->get()
            ->getRowArray();


        $nrcp_members = $db->table('nrcp_members as nrcp')
            ->select('p.id, p.honorifics, p.first_name, p.middle_name, p.last_name')
            ->join('persons as p', 'p.id = nrcp.person_id', 'left')
            ->where('nrcp.institution_id', $id)
            ->get()
            ->getResultArray();

        $balik_scientists = $db->table('balik_scientist_engaged as bse')
            ->select('p.id, p.honorifics, p.first_name, p.middle_name, p.last_name')
            ->join('persons as p', 'p.id = bse.person_id', 'left')
            ->where('bse.institution_id', $id)
            ->get()
            ->getResultArray();

        $consortium = $db->table('consortium_members as cm')
            ->select('con.name as consortium_name')
            ->join('consortiums as con', 'con.id = cm.consortium_id', 'left')
            ->where('cm.institution_id', $id)
            ->get()
            ->getRowArray();

        $completed_research_projects = $db->table('research_projects as rp')
            ->select('rp.name as research_project_name, rp.description, rp.status, rp.sector, rp.project_objectives, rp.duration, rp.project_leader, rp.approved_amount')
            ->where('rp.institution_id', $id)
            ->where('rp.status', 'Completed')
            ->get()
            ->getResultArray();

        $ongoing_research_projects = $db->table('research_projects as rp')
            ->select('rp.name as research_project_name, rp.description, rp.status, rp.sector, rp.project_objectives, rp.duration, rp.project_leader, rp.approved_amount')
            ->where('rp.institution_id', $id)
            ->where('rp.status', 'Ongoing')
            ->get()
            ->getResultArray();

        if (!$institution) {
            return redirect()->to('/institution/home')->with('error', 'Institution not found.');
        }

        return view('institution/details', [
            'institution' => $institution,
            'nrcp_members' => $nrcp_members,
            'balik_scientists' => $balik_scientists,
            'consortium' => $consortium,
            'completed_research_projects' => $completed_research_projects,
            'ongoing_research_projects' => $ongoing_research_projects
        ]);

    }

}


