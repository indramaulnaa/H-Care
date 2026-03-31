<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AkunPuskesmasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = User::where('role', 'admin_puskesmas');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nama_unit', 'like', "%{$search}%");
            });
        }

        $akunPuskesmas = $query->latest()->paginate(15);
        
        $listPuskesmas = [
            'Puskesmas Batang I', 'Puskesmas Batang II', 'Puskesmas Batang III', 'Puskesmas Batang IV',
            'Puskesmas Warungasem', 'Puskesmas Wonotunggal', 'Puskesmas Bandar I', 'Puskesmas Bandar II',
            'Puskesmas Blado I', 'Puskesmas Blado II', 'Puskesmas Reban', 'Puskesmas Bawang',
            'Puskesmas Tersono', 'Puskesmas Gringsing I', 'Puskesmas Gringsing II', 'Puskesmas Limpung',
            'Puskesmas Banyuputih', 'Puskesmas Subah', 'Puskesmas Pecalungan', 'Puskesmas Kandeman', 
            'Puskesmas Tulis'
        ];

        return view('dinkes.akun_puskesmas', compact('akunPuskesmas', 'search', 'listPuskesmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'nama_unit' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@hcare.com', // <-- Trik: Membuat email dummy otomatis
            'password' => Hash::make($request->password),
            'role' => 'admin_puskesmas',
            'nama_unit' => $request->nama_unit
        ]);

        return back()->with('success', 'Akun Puskesmas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'nama_unit' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@hcare.com', // <-- Update email dummy juga
            'nama_unit' => $request->nama_unit
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'Data Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun Puskesmas berhasil dihapus secara permanen.');
    }
}