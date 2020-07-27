<?php

declare(strict_types=1);

namespace App;

use CallbackFilterIterator;
use DirectoryIterator;
use IteratorIterator;
use SimpleXMLElement;
use XMLWriter;

final class UserRepository
{
    private string $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../database/';
    }

    public function save(User $user): void
    {
        $xw = new XMLWriter();
        $xw->openMemory();
        $xw->startDocument("1.0", "UTF-8");
        $xw->startElement('user');
        $xw->startElement('id');
        $xw->text($user->id);
        $xw->endElement();
        $xw->startElement("name");
        $xw->text($user->name);
        $xw->endElement();
        $xw->startElement("login");
        $xw->text($user->login);
        $xw->endElement();
        $xw->startElement("email");
        $xw->text($user->email);
        $xw->endElement();
        $xw->startElement("passwordHash");
        $xw->text($user->passwordHash);
        $xw->endElement();
        $xw->endElement();
        $xw->endDocument();
        $xml = $xw->outputMemory();

        $fileName = $this->fileName($user->id);
        file_put_contents($fileName, $xml);
    }

    public function findByLogin(string $login): ?User
    {
        $iterator = $this->iterator();
        foreach ($iterator as $xml) {
            /** @var SimpleXMLElement $xml */
            if ((string) $xml->login === $login) {
                return $this->xmlToUser($xml);
            }
        }
        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $email = mb_strtolower($email);

        $iterator = $this->iterator();
        foreach ($iterator as $xml) {
            /** @var SimpleXMLElement $xml */
            $value = (string)$xml->email;
            $value = mb_strtolower($value);
            if ($value === $email) {
                return $this->xmlToUser($xml);
            }
        }
        return null;
    }

    public function nextId(): string
    {
        do {
            $id = uniqid();
            $fileName = $this->fileName($id);
        } while (file_exists($fileName));

        return $id;
    }

    private function fileName(string $id): string
    {
        return $this->dir . $id . '.xml';
    }

    private function xmlToUser(SimpleXMLElement $xml): User
    {
        return new User(
            (string) $xml->id,
            (string) $xml->login,
            (string) $xml->passwordHash,
            (string) $xml->email,
            (string) $xml->name
        );
    }

    private function iterator(): iterable
    {
        $iterator = new DirectoryIterator($this->dir);
        $iterator = new CallbackFilterIterator($iterator, function (DirectoryIterator $file) {
            return ($file->isFile() && $file->getExtension() === 'xml');
        });
        $iterator = new class ($iterator) extends IteratorIterator {
            public function current()
            {
                /** @var DirectoryIterator $file */
                $file = parent::current();
                return simplexml_load_file($file->getRealPath());
            }
        };
        return $iterator;
    }

    public function findById(string $id): ?User
    {
        $fileName = $this->fileName($id);
        if (! file_exists($fileName)) {
            return null;
        }
        $xml = simplexml_load_file($fileName);

        return $this->xmlToUser($xml);
    }
}