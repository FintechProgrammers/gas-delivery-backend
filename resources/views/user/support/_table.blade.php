@forelse ($tickets as $item)
    <tr>
        <td>
            #{{ $item->ticket_code }}
        </td>
        <td>
            {{ optional($item->subject)->name }}
        </td>
        <td>
            {{ $item->created_at->format('jS,M Y H:i A') }}
        </td>
        <td>
            @if ($item->status === 'open')
                <span class="badge bg-success">Open</span>
            @elseif ($item->status === 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif ($item->status === 'closed')
                <span class="badge bg-danger">Closed</span>
            @endif
        </td>
        <td>
            <a aria-label="anchor" href="javascript:void(0);" class="" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-three-dots fs-22"></i>
            </a>
            <ul class="dropdown-menu" style="">
                <li class="mb-0">
                    <a href="{{ route('tickets.show', $item->uuid) }}" class="dropdown-item">Details</a>
                </li>
                <li class="mb-0">
                    <a href="javascript:void(0);" class="dropdown-item btn-action"
                        data-url="{{ route('tickets.delete', $item->uuid) }}"
                        data-action="you want to delete {{ $item->name }}">Delete</a>
                </li>
            </ul>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center"><span class="text-warning">no data available</span></td>
    </tr>
@endforelse
<tr style="border: none;">
    <td colspan="5" style="border: none;">
        {{ $tickets->links('vendor.pagination.custom') }}
    </td>
</tr>
