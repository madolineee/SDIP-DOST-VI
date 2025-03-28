<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProjectsController extends BaseController
{
    public function projects()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('research_projects');
        $builder->select('
        research_projects.id, 
        research_projects.name, 
        research_projects.description, 
        research_projects.status
    ');

        $data['research_projects'] = $builder->get()->getResultArray();

        return view('institution/projects/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        // Fetch all institutions for the dropdown
        $institutions = $db->table('stakeholders')->select('id, name')->get()->getResult();

        return view('institution/projects/create', ['institutions' => $institutions]);
    }

    public function storeProjects()
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        $researchData = [
            'institution_id' => $this->request->getPost('institution'),
            'name' => $this->request->getPost('name'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        $db->table('research_projects')->insert($researchData);
        $personId = $db->insertID();

        return redirect()->to('/institution/projects/index')->with('success', 'Institution added successfully!');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $project = $db->table('research_projects as p')
            ->select('p.id as project_id, p.name as research_name, p.status, p.description,
                      i.id as institution_id, 
                      s.id as stakeholder_id, s.name')
            ->join('institutions as i', 'i.id = p.institution_id', 'left')
            ->join('stakeholders as s', 's.id = i.stakeholder_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$project) {
            return redirect()->to('/institution/projects')->with('error', 'Project not found.');
        }

        $institutions = $db->table('stakeholders')->select('id, name')->get()->getResultArray();

        return view('institution/projects/edit', ['project' => $project, 'institutions' => $institutions]);
    }

    public function update($id)
{
    $db = \Config\Database::connect();
    $timestamp = date('Y-m-d H:i:s');

    $data = [
        'name' => $this->request->getPost('research_name'),
        'status' => $this->request->getPost('status'),
        'description' => $this->request->getPost('description'),
        'institution_id' => $this->request->getPost('institution'),
        'updated_at' => $timestamp
    ];

    $db->table('research_projects')->where('id', $id)->update($data);

    return redirect()->to('/institution/projects/index')->with('success', 'Project updated successfully.');
}

}
