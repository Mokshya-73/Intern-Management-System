@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Complaints</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($complaints->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Intern</th>
                        <th>Complaint</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->id }}</td>
                            <td>{{ $complaint->intern->name ?? 'No Name' }}</td>
                            <td>{{ $complaint->complaint }}</td>
                            <td>{{ $complaint->supervisor->name ?? 'No Name' }}</td>
                            <td>{{ ucfirst($complaint->status) }}</td>
                            <td>
                                @if($complaint->status === 'pending')
                                    <form action="{{ route('complaints.remove', $complaint->id) }}" method="POST">
                                        @csrf
                                        <textarea name="reason_for_removal" placeholder="Enter reason" required></textarea>
                                        <button type="submit" class="btn btn-danger btn-sm">Resolve</button>
                                    </form>
                                @else
                                    <span class="text-success">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No complaints available.</p>
        @endif
    </div>
@endsection
