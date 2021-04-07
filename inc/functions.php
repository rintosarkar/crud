<?php

define('DB_NAME', 'D:\xampp\htdocs\php\hasinbhai\crud\data\db.txt');

function seed()
{
    $data = [
        [
            'id' => 1,
            'fname' => 'Bayzid',
            'lname' => 'Ahamed',
            'roll' => '12',
        ],
        [
            'id' => 2,
            'fname' => 'Tarez',
            'lname' => 'Anam',
            'roll' => '8',
        ],
        [
            'id' => 3,
            'fname' => 'Joni',
            'lname' => 'Khan',
            'roll' => '4',
        ],
        [
            'id' => 4,
            'fname' => 'Sharmin',
            'lname' => 'Tonny',
            'roll' => '11',
        ],
        [
            'id' => 5,
            'fname' => 'Aonna',
            'lname' => 'Ahamed',
            'roll' => '10',
        ],
        [
            'id' => 6,
            'fname' => 'Lamia',
            'lname' => 'Sarkar',
            'roll' => '7',
        ],
        [
            'id' => 7,
            'fname' => 'Shiquat',
            'lname' => 'Sarkar',
            'roll' => '5',
        ],
        [
            'id' => 8,
            'fname' => 'Tahmid',
            'lname' => 'Sarkar',
            'roll' => '12',
        ],
        [
            'id' => 9,
            'fname' => 'Afrin',
            'lname' => 'Akter',
            'roll' => '14',
        ],
        [
            'id' => 10,
            'fname' => 'Iman',
            'lname' => 'Ahmed',
            'roll' => '13',
        ],
        [
            'id' => 11,
            'fname' => 'Adhian',
            'lname' => 'Sarkar',
            'roll' => '2',
        ],
        [
            'id' => 12,
            'fname' => 'Tanisha',
            'lname' => 'Sarkar',
            'roll' => '1',
        ],
    ];
    $serializedData = serialize($data);

    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

function generateReport()
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
            <th width="25%">Action</th>
        </tr>
        <?php foreach ($students as $student) { ?>
            <tr>
                <td><?php printf(
                    '%s %s',
                    $student['fname'],
                    $student['lname']
                ); ?></td>
                <td><?php printf('%s', $student['roll']); ?></td>
                <td>
                    <?php printf(
                        '<a href="index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="index.php?task=delete&id=%s">Delete</a> ',
                        $student['id'],
                        $student['id']
                    ); ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php
}

function addStudent($fname, $lanme, $roll)
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    $newId = getNewId($students);

    $found = false;
    foreach ($students as $_student) {
        if ($_student['roll'] == $roll) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        $student = [
            'id' => $newId,
            'fname' => $fname,
            'lname' => $lanme,
            'roll' => $roll,
        ];

        array_push($students, $student);
        $serializedData = serialize($students);

        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }

    return false;
}

function getStudent($id)
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach ($students as $student) {
        if ($student['id'] == $id) {
            return $student;
        }
    }
    return false;
}

function updateStudent($id, $fname, $lname, $roll)
{
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach ($students as $_student) {
        if ($_student['roll'] == $roll && $_student['id'] != $id) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        $students[$id - 1]['fname'] = $fname;
        $students[$id - 1]['lname'] = $lname;
        $students[$id - 1]['roll'] = $roll;

        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }

    return false;
}

function deleteStudent($id)
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach ($students as $offset => $student) {
        if ($student['id'] == $id) {
            unset($students[$offset]);
        }
    }

    $serializedData = serialize($students);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

function printRaw()
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    print_r($students);
}

function getNewId($students)
{
    $maxId = max(array_column($students, 'id'));
    return $maxId + 1;
}
