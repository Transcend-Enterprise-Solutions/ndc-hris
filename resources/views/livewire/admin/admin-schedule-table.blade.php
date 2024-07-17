<div>
    <h2>Employee Schedules</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>WFH Days</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->emp_code }}</td>
                    <td>{{ $schedule->wfh_days }}</td>
                    <td>{{ $schedule->default_start_time }}</td>
                    <td>{{ $schedule->default_end_time }}</td>
                    <td>{{ $schedule->start_date }}</td>
                    <td>{{ $schedule->end_date }}</td>
                    <td>
                        <button wire:click="edit({{ $schedule->id }})">Edit</button>
                        <button wire:click="delete({{ $schedule->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($scheduleId)
        <h3>Edit Schedule</h3>
        <form wire:submit.prevent="update">
            <div>
                <label>Employee Code</label>
                <input type="text" wire:model="emp_code" required>
            </div>
            <div>
                <label>WFH Days</label>
                <input type="text" wire:model="wfh_days" placeholder="e.g., Monday,Thursday">
            </div>
            <div>
                <label>Default Start Time</label>
                <input type="time" wire:model="default_start_time">
            </div>
            <div>
                <label>Default End Time</label>
                <input type="time" wire:model="default_end_time">
            </div>
            <div>
                <label>Start Date</label>
                <input type="date" wire:model="start_date" required>
            </div>
            <div>
                <label>End Date</label>
                <input type="date" wire:model="end_date" required>
            </div>
            <button type="submit">Update Schedule</button>
            <button type="button" wire:click="resetInputFields">Cancel</button>
        </form>
    @endif
</div>
