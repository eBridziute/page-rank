@extends('layouts.app')

@section('title', 'Page ranks')

@section('content')
    <div class="jumbotron page-header">
        <h1>Page ranks</h1>
    </div>
    <div>
        @include('components.search')
    </div>
    <div>
        @if(0 < $pageRanks->count())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Rank</th>
                    <th>Domain</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pageRanks as $pageRank)
                    <tr>
                        <td>{{ $pageRank->rank }}</td><td>{{ $pageRank->domain }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>
                No data found
            </div>
       @endif
    </div>
    @if($pageRanks->links())
        @include('components.page_links', ['item' => $pageRanks])
    @endif
@endsection
