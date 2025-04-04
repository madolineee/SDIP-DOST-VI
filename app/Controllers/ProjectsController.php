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
        research_projects.status,
        research_projects.sector,
        research_projects.project_objectives,
        research_projects.duration,
        research_projects.project_leader,
        research_projects.approved_amount
    ');

        $data['research_projects'] = $builder->get()->getResultArray();

        return view('institution/projects/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        // Fetch all institutions for the dropdown
        $institutions = $db->table('institutions i')
            ->select('i.id, s.name')
            ->join('stakeholders s', 's.id = i.stakeholder_id', 'left')
            ->get()
            ->getResult();

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
            'sector' => $this->request->getPost('sector'),
            'project_objectives' => $this->request->getPost('project_objectives'),
            'duration' => $this->request->getPost('duration'),
            'project_leader' => $this->request->getPost('project_leader'),
            'approved_amount' => $this->request->getPost('approved_amount'),
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        $db->table('research_projects')->insert($researchData);

        return redirect()->to('/institution/projects/index')->with('success', 'Institution added successfully!');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $project = $db->table('research_projects as p')
            ->select('p.id as project_id, p.name as research_name, p.status, p.description, p.sector, p.duration, p.project_leader, p.project_objectives, p.approved_amount,
                      i.id as institution_id, s.name as institution_name')
            ->join('institutions as i', 'i.id = p.institution_id', 'left')
            ->join('stakeholders s', 's.id = i.stakeholder_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$project) {
            return redirect()->to('/institution/projects')->with('error', 'Project not found.');
        }

        $institutions = $db->table('institutions i')->select('i.id, s.name')
            ->join('stakeholders s', 's.id = i.stakeholder_id', 'left')
            ->get()
            ->getResult();

        return view('institution/projects/edit', ['project' => $project, 'institutions' => $institutions]);
    }
    public function update($id)
    {
        $db = \Config\Database::connect();
        $timestamp = date('Y-m-d H:i:s');

        $existingScientist = $db->table('research_projects')->where('id', $id)->get()->getRowArray();
        if (!$existingScientist) {
            return redirect()->to('/institution/projects')->with('error', 'Research not found.');
        }

        $data = [
            'institution_id' => $this->request->getPost('institution'),
            'name' => $this->request->getPost('research_name'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'sector' => $this->request->getPost('sector'),
            'duration' => $this->request->getPost('duration'),
            'project_leader' => $this->request->getPost('project_leader'),
            'project_objectives' => $this->request->getPost('project_objectives'),
            'approved_amount' => $this->request->getPost('approved_amount'),
            'updated_at' => $timestamp
        ];
        $db->table('research_projects')->where('id', $id)->update($data);

        return redirect()->to('/institution/projects/index')->with('success', 'Research updated successfully.');        
    }



    public function delete($id)
    {
        $db = \Config\Database::connect();

        $project = $db->table('research_projects')->where('id', $id)->get()->getRowArray();

        if (!$project) {
            return redirect()->to('/institution/projects')->with('error', 'Project not found.');
        }

        $db->table('research_projects')->where('id', $id)->delete();

        return redirect()->to('/institution/projects/index')->with('success', 'Project deleted successfully.');
    }

    public function view($id)
    {
        $db = \Config\Database::connect();

        $project = $db->table('research_projects as p')
            ->select('p.id as project_id, p.name as research_name, p.status, p.description, p.sector, p.duration, p.project_leader, p.project_objectives, p.approved_amount,
                  s.id as stakeholder_id, s.name as stakeholder_name')
            ->join('institutions as i', 'i.id = p.institution_id', 'left')
            ->join('stakeholders as s', 's.id = i.stakeholder_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$project) {
            return redirect()->to('/institution/projects')->with('error', 'Project not found.');
        }

        return view('institution/projects/details', ['project' => $project]);
    }

}
