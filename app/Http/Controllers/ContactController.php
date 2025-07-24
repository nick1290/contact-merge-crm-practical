<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMerge;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::get();
        return view('contact.index', compact('contacts'));
    }

    public function masterData($id)
    {
        $contacts = Contact::where([['id', '<>', $id], ['status', '<>', 'merged']])->get();
        return response()->json($contacts, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function renderContact(Request $request)
    {
        $query = Contact::query();

        if ($request->name) $query->where('name', 'like', "%{$request->name}%");
        if ($request->email) $query->where('email', 'like', "%{$request->email}%");
        if ($request->gender) $query->where('gender', $request->gender);

        $contacts = $query->get();

        $contacts->map(function ($data) {
            $checkData = ContactMerge::where('master_contact_id', $data->id)->value('created_at');
            $data->merged_at = $checkData ? date('d/m/Y h:m', strtotime($checkData)) : NULL;
        });
        return response()->json($contacts, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|unique:contacts,email',
            'phone' => 'required|string|unique:contacts,phone',
            'gender' => 'required|in:M,F',
            'company_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'doc' => 'required|mimes:pdf,doc,docx',
        ]);

        // Upload files
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('contacts/images', 'public')
            : null;

        $docPath = $request->hasFile('doc')
            ? $request->file('doc')->store('contacts/docs', 'public')
            : null;

        // Create Contact
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'company_name' => $request->company_name,
            'image' => $imagePath,
            'doc' => $docPath,
        ]);

        // Create Address
        $contact->address()->create([
            'city' => $request->city,
            'landmark' => $request->landmark,
            'pincode' => $request->pincode,
            'state' => $request->state,
            'country' => $request->country,
            'address' => $request->textarea,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact created successfully'
        ]);
    }

    public function mergeContacts(Request $request)
    {
        $masterId = $request->master_id;
        $secondaryId = $request->secondary_id;

        $master = Contact::findOrFail($masterId);
        $secondary = Contact::findOrFail($secondaryId);

        ContactMerge::create([
            'master_contact_id' => $master->id,
            'merged_contact_id' => $secondary->id,
            'merged_contact_data' => json_encode($master)
        ]);

        $mergedMasterSecondaryContact = Contact::whereId($masterId)->update([
            'name' => $secondary->name,
            'email' => $secondary->email,
            'phone' => $secondary->phone,
            'gender' => $secondary->gender,
            'company_name' => $secondary->company_name,
            'dob' => $secondary->dob,
            'image' => $secondary->image,
            'doc' => $secondary->doc
        ]);

        $master->update(['status' => 'merged']);
        $secondary->update(['status' => 'master']);

        return response()->json(['message' => 'Contact merged successfully']);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contact::whereId($id)->first();
        return response()->json($contact);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contact.create');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $contact = Contact::findOrFail($request->contact_id);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles');
        }

        if ($request->hasFile('document_file')) {
            $data['document_file'] = $request->file('document_file')->store('documents');
        }

        $contact->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        $contact->address()->delete();

        return response()->json(['message' => 'Contact deleted successfully.']);
    }
}
