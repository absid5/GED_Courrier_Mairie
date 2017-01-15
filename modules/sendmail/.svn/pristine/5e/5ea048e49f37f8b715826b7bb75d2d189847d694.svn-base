<?php
/*
*    Copyright 2008-2016 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

abstract class EmailSignaturesAbstract extends Database
{

    protected function rawToHtml($text) {
        $text = str_replace("\r\n", PHP_EOL, $text);
        $text = str_replace("\r", PHP_EOL, $text);
        $text = str_replace('###', ';', $text);
        $text = str_replace('___', '--', $text);

        return $text;
    }

    public function createForCurrentUser($title, $body) {
        $db = new Database();

        $db->query('INSERT INTO ' . EMAIL_SIGNATURES_TABLE . ' (user_id, html_body, title) VALUES (?, ?, ?)',
            [$_SESSION['user']['UserId'], $body, $title]
        );
    }

    public function getForCurrentUser() {
        $db = new Database();

        $stmt = $db->query('SELECT * FROM ' .EMAIL_SIGNATURES_TABLE. ' WHERE user_id = ? order by title',
            [$_SESSION['user']['UserId']]
        );
        $mailSignatures = [];
        while($res = $stmt->fetchObject())
            $mailSignatures[] = ['id' => $res->id, 'title' => $res->title, 'signature' => $this->rawToHtml($res->html_body)];

        return $mailSignatures;
    }

    public function updateForCurrentUser($id, $body) {
        $db = new Database();

        $db->query('UPDATE ' . EMAIL_SIGNATURES_TABLE . ' SET user_id = ?, html_body = ? WHERE id = ?',
            [$_SESSION['user']['UserId'], $body, $id]
        );
    }

    public function deleteForCurrentUser($id) {
        $db = new Database();

        $db->query('DELETE FROM ' . EMAIL_SIGNATURES_TABLE . ' WHERE user_id = ? AND id = ?',
            [$_SESSION['user']['UserId'], $id]
        );
    }
}