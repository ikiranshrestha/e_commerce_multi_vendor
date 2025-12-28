<h2>Import Completed</h2>

<p>Your product import has finished.</p>

<ul>
    <li>Status: {{ $import->status }}</li>
    <li>Processed Rows: {{ $import->processed_rows }}</li>
    <li>Failed Rows: {{ $import->failed_rows }}</li>
</ul>

<p>You can review details in the dashboard.</p>
