<?php

class SCI_Media
{
    /**
     * @param $file
     *
     * @return array
     */
    public function read_file($file): array
    {
        $csv = fopen($file, 'r');

        $delimiter = ',';
        $rows = array();
        $row_number = 0;
        while ($csv_row = fgetcsv($csv, 0, $delimiter)) {
            $row_number++;
            $rows[] = $csv_row;
        }

        return $rows;
    }

    public function validator($file): bool
    {
        $isError = true;

        if (!empty($file['error'])) {
            $isError = false;
        }

        if ($file['size'] >= 3145728) {
            $isError = false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!empty($extension) && strtolower($extension) != 'csv') {
            $isError = false;
        }

        return $isError;
    }
}