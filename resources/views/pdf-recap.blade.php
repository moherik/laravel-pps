<!DOCTYPE html>

<head>
  <title>{{  $title }} - {{ now()->format('d-m-Y') }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>
    table {
      width: 100%;
      text-align: left;
    }

    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }

    table thead {
      text-align: center;
    }

    p,
    .title {
      margin: 0px;
      padding: 0px;
    }

    section.top {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  @php
    $totalValidVotes = 0;
    $totalInvalidVotes = 0;
    $totalVotes = 0;
  @endphp

  <section class="top">
    <h4 class="title">{{ $title }}</h4>
    <p>Dibuat pada: {{ now()->format('h:i:s d-m-Y') }}</p>
  </section>

  <table class="table table-bordered">
    <thead>
      <tr>      
        <th title="Field #1" width="30">No.</th>
        <th title="Field #2" align="left">Room</th>
        <th title="Field #3" width="80">Sah</th>
        <th title="Field #4" width="80">Tidak sah</th>
        <th title="Field #5" width="80">Total suara</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td></td>
        <td> </td>
        <td></td>
        <td> </td>
        <td> </td>
      </tr>
      @foreach($rooms as $room)
        <tr>
          <td align="center" rowspan="{{ 2+count($room->candidates) }}">{{$loop->iteration}}.</td>
          <td colspan="4"><strong>{{$room->room_name}}</strong></td>
        </tr>
        @foreach($room->candidates as $candidate)
          <tr>
            <td>{{$loop->iteration}}. {{ $candidate->name }}</td>
            <td align="center">{{ $candidate->votes->sum('total') }} ({{ $candidate->percentage() }}%)</td>
            <td align="center">-</td>
            <td align="center">-</td>
          </tr>
        @endforeach
        <tr>
          <td>Total surat suara</td>
          <td align="center">{{ $room->validVotes() }}</td>
          <td align="center">{{ $room->invalidVotes() }}</td>
          <td align="center">{{ $room->totalVotes() }}</td>
        </tr>

        @php
          $totalValidVotes = $totalValidVotes + $room->validVotes();
          $totalInvalidVotes = $totalInvalidVotes + $room->invalidVotes();
          $totalVotes = $totalVotes + $room->totalVotes();
        @endphp
      @endforeach
      <tr>
        <td></td>
        <td> </td>
        <td></td>
        <td> </td>
        <td> </td>
      </tr>
      <tr style="font-weight: bold;">
        <td align="right"></td>
        <td>Total suara</td>
        <td align="center">{{$totalValidVotes}}</td>
        <td align="center">{{$totalInvalidVotes}}</td>
        <td align="center">{{$totalVotes}}</td>
      </tr>
    </tbody>
  </table>

</body>

</html>