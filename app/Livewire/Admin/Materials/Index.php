<?php

namespace App\Livewire\Admin\Materials;

use App\Models\Material;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingMaterial = null;

    // Campos del formulario
    public $code = '';
    public $name = '';
    public $unit = '';
    public $unit_price = '';

    protected $rules = [
        'code' => 'required|string|max:20|unique:materials,code',
        'name' => 'required|string|max:100|unique:materials,name',
        'unit' => 'required|string|max:50',
        'unit_price' => 'required|numeric|min:0'
    ];

    protected $messages = [
        'code.required' => 'El código del material es obligatorio',
        'code.unique' => 'Este código ya existe',
        'name.required' => 'El nombre del material es obligatorio',
        'name.unique' => 'Este nombre ya existe',
        'name.max' => 'El nombre no puede exceder 100 caracteres',
        'unit.required' => 'La unidad es obligatoria',
        'unit.max' => 'La unidad no puede exceder 50 caracteres',
        'unit_price.required' => 'El precio unitario es obligatorio',
        'unit_price.numeric' => 'El precio debe ser un número',
        'unit_price.min' => 'El precio debe ser mayor o igual a 0'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal(Material $material)
    {
        $this->editingMaterial = $material;
        $this->code = $material->code;
        $this->name = $material->name;
        $this->unit = $material->unit;
        $this->unit_price = $material->unit_price;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingMaterial = null;
    }

    public function createMaterial()
    {
        $this->validate();

        Material::create([
            'code' => $this->code,
            'name' => $this->name,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price
        ]);

        $this->closeCreateModal();
        session()->flash('success', 'Material creado exitosamente');
    }

    public function updateMaterial()
    {
        // Actualizar reglas de validación para edición
        $this->rules['code'] = 'required|string|max:20|unique:materials,code,' . $this->editingMaterial->id;
        $this->rules['name'] = 'required|string|max:100|unique:materials,name,' . $this->editingMaterial->id;
        
        $this->validate();

        $this->editingMaterial->update([
            'code' => $this->code,
            'name' => $this->name,
            'unit' => $this->unit,
            'unit_price' => $this->unit_price
        ]);

        $this->closeEditModal();
        session()->flash('success', 'Material actualizado exitosamente');
    }

    public function deleteMaterial(Material $material)
    {
        $material->delete();
        session()->flash('success', 'Material eliminado exitosamente');
    }

    public function initializeDefaultMaterials()
    {
        $defaultMaterials = [
            ['code' => 'VIG_PRIN', 'name' => 'viga_principal_sujecion', 'unit' => 'unidad', 'unit_price' => 100],
            ['code' => 'VIG_SEC', 'name' => 'viga_secundaria', 'unit' => 'unidad', 'unit_price' => 100],
            ['code' => 'COL_ALU', 'name' => 'columna', 'unit' => 'unidad', 'unit_price' => 100],
            ['code' => 'ANI_ALU', 'name' => 'anillo', 'unit' => 'unidad', 'unit_price' => 100],
            ['code' => 'CAN_AGU', 'name' => 'canal_agua', 'unit' => 'unidad', 'unit_price' => 91],
            ['code' => 'MAL_ALU', 'name' => 'malla', 'unit' => 'kg', 'unit_price' => 3.15],
            ['code' => 'ALU_CAN', 'name' => 'alucobond_canal', 'unit' => 'unidad', 'unit_price' => 22.31],
            ['code' => 'ANC_QUI', 'name' => 'ancla', 'unit' => 'unidad', 'unit_price' => 24],
            ['code' => 'TAC_PAR', 'name' => 'tacos', 'unit' => 'unidad', 'unit_price' => 0.03],
            ['code' => 'TOR_PAR', 'name' => 'tornillos_pared', 'unit' => 'unidad', 'unit_price' => 0.06],
            ['code' => 'TOR_PIS', 'name' => 'tornillos_piso', 'unit' => 'unidad', 'unit_price' => 0.06],
            ['code' => 'TOR_ALU', 'name' => 'tornillos_aluminio', 'unit' => 'unidad', 'unit_price' => 0.08],
            ['code' => 'FLE_MET', 'name' => 'fleje_metalico', 'unit' => 'ml', 'unit_price' => 6],
            ['code' => 'AQU_PRO', 'name' => 'aquaprotect', 'unit' => 'unidad', 'unit_price' => 81],
            ['code' => 'AND_ALQ', 'name' => 'andamios', 'unit' => 'día', 'unit_price' => 1.25],
            ['code' => 'PER_T', 'name' => 't', 'unit' => 'unidad', 'unit_price' => 5.42],
            ['code' => 'ANG_ALU', 'name' => 'angulo', 'unit' => 'unidad', 'unit_price' => 7],
            ['code' => 'CIN_DOB', 'name' => 'cinta_doble_faz', 'unit' => 'unidad', 'unit_price' => 9],
            ['code' => 'SIL_SEL', 'name' => 'silicon_sellante', 'unit' => 'unidad', 'unit_price' => 4],
            ['code' => 'SIL_COL', 'name' => 'silicon_color', 'unit' => 'unidad', 'unit_price' => 4],
            ['code' => 'MAS_TAP', 'name' => 'masking', 'unit' => 'unidad', 'unit_price' => 1.1],
            ['code' => 'VID_TEM', 'name' => 'vidrio', 'unit' => 'm²', 'unit_price' => 25],
            ['code' => 'ALU_BAN', 'name' => 'alumband', 'unit' => 'unidad', 'unit_price' => 10],
            ['code' => 'TUB_PVC', 'name' => 'tubo_pvc_3', 'unit' => 'unidad', 'unit_price' => 6],
            ['code' => 'COD_45', 'name' => 'codo_pvc_45_3', 'unit' => 'unidad', 'unit_price' => 4],
            ['code' => 'COD_90', 'name' => 'codo_pvc_90_3', 'unit' => 'unidad', 'unit_price' => 4],
            ['code' => 'CAL_PVC', 'name' => 'calipega', 'unit' => 'unidad', 'unit_price' => 3],
            ['code' => 'PLA_NEG', 'name' => 'plastico_negro', 'unit' => 'm²', 'unit_price' => 0.58],
            ['code' => 'PER_M2', 'name' => 'pergola', 'unit' => 'm²', 'unit_price' => 18],
            ['code' => 'COL_UNI', 'name' => 'columnas', 'unit' => 'unidad', 'unit_price' => 5],
            ['code' => 'BAJ_PVC', 'name' => 'n_bajantes', 'unit' => 'unidad', 'unit_price' => 10],
            ['code' => 'ALU_BON', 'name' => 'aluco_bond', 'unit' => 'unidad', 'unit_price' => 10],
            ['code' => 'ANI_UNI', 'name' => 'anillos', 'unit' => 'unidad', 'unit_price' => 5],
            
            ['code' => 'TORN_CUADRICULA', 'name' => 'tornillos_cuadricula', 'unit' => 'unidad', 'unit_price' => 0.06],
            ['code' => 'TORN_T_CUADRICULA', 'name' => 'tornillos_t', 'unit' => 'unidad', 'unit_price' => 0.06],
            ['code' => 'CUADRICULA', 'name' => 'cuadricula', 'unit' => 'unidad', 'unit_price' => 10],
            ['code' => 'MANO_OBRA_CUADRICULA', 'name' => 'mano_de_obra_cuadricula', 'unit' => 'unidad', 'unit_price' => 3],
            ['code' => 'MANO_OBRA_CUADRICULA_TRAMA', 'name' => 'mano_de_obra_cuadricula_trama', 'unit' => 'unidad', 'unit_price' => 5],
            ['code' => 'TEJA_ASFALTICA', 'name' => 'teja_asfaltica', 'unit' => 'unidad', 'unit_price' => 33],
            ['code' => 'MADERA_RH', 'name' => 'madera_rh', 'unit' => 'unidad', 'unit_price' => 99.5],
            ['code' => 'TORNILLO_CAPUCHON', 'name' => 'tornillo_capuchon', 'unit' => 'unidad', 'unit_price' => 0.9],
            ['code' => 'UNION_POLICARBONATO', 'name' => 'union_policarbonato', 'unit' => 'unidad', 'unit_price' => 85],
            ['code' => 'CINTA_FILTRO', 'name' => 'cinta_filtro', 'unit' => 'unidad', 'unit_price' => 36],
            ['code' => 'POLICARBONATO', 'name' => 'policarbonato', 'unit' => 'm²', 'unit_price' => 15],
        ];

        foreach ($defaultMaterials as $material) {
            Material::firstOrCreate(
                ['name' => $material['name']],
                $material
            );
        }

        session()->flash('success', 'Materiales por defecto inicializados exitosamente');
    }

    private function resetForm()
    {
        $this->code = '';
        $this->name = '';
        $this->unit = '';
        $this->unit_price = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $materials = Material::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('unit', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.materials.index', compact('materials'));
    }
}
