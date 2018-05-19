<?php
namespace Walltwisters\lib\interfacesrepo;

interface IRepositoryFactory {
    public function getRepository($repositoryName);
}
